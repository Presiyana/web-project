<?php
require_once __DIR__ . '/../database/DatabaseConnection.php';
require_once __DIR__ . '/../config/lang_config.php';

class RequirementService
{
    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new RequirementService();
        }
        return self::$instance;
    }

    private $db;
    private function __construct()
    {
        $this->db = DatabaseConnection::getInstance()->getConnection();
    }

    public function getRequirements()
    {
        $sql = "SELECT r.*, i.indicator_name, i.unit, i.value, i.indicator_description
                FROM `requirements` r
                LEFT JOIN `indicators` i ON r.id = i.requirement_id";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetchAll();
        return $result;
    }

    public function addRequirement(
        $title,
        $description,
        $hashtags,
        $priority,
        $layer,
        $isNonFunctional,
        $indicators = array(),
    ) {
        $sql = "INSERT INTO `requirements` (`title`, `description`, `hashtags`, `priority`, `layer`, `isNonFunctional`) 
                VALUES (?,?,?,?,?,?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$title, $description, $hashtags, $priority, $layer, (int) $isNonFunctional]);

        $requirementId = $this->db->lastInsertId();

        if ($isNonFunctional && !empty($indicators)) {
            foreach ($indicators as $indicator) {
                $this->addIndicatorForRequirement(
                    $requirementId,
                    $indicator['indicator_name'],
                    $indicator['unit'],
                    $indicator['value'],
                    $indicator['indicator_description']
                );
            }
        }
        return $requirementId;
    }

    public function addIndicatorForRequirement(
        $requirementId,
        $indicator_name,
        $unit,
        $value,
        $indicator_description
    ) {
        $indicator_name = $indicator_name ?? 'N/A';
        $unit = $unit ?? 'N/A';
        $value = $value ?? 'N/A';
        $indicator_description = $indicator_description ?? 'N/A';

        $sql = "INSERT INTO `indicators` (`requirement_id`, `indicator_name`, `unit`, `value`, `indicator_description`) 
                VALUES (:requirement_id, :indicator_name, :unit, :value, :indicator_description)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':requirement_id' => $requirementId,
            ':indicator_name' => $indicator_name,
            ':unit' => $unit,
            ':value' => $value,
            ':indicator_description' => $indicator_description
        ]);
    }

    public function removeIndicatorFromRequirement($id)
    {
        try {

            $sql = "DELETE FROM `indicators` WHERE `id` = :id";
            $stmp = $this->db->prepare($sql);
            $stmp->execute([':id' => $id]);

            if (!$stmp->rowCount()) {
                throw new Exception($translations['requirement_deletion_failed'] ?? "Requirement deletion failed.");
            }
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getRequirementById($id)
    {
        $sql = "SELECT * FROM requirements WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        $requirement = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$requirement) {
            throw new Exception($translations['req_not_found'] ?? "Requirement not found.");
        }
        return $requirement;
    }

    public function getRequirementIndicators($id)
    {
        $sql = "SELECT * FROM `indicators` WHERE requirement_id = :requirement_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':requirement_id' => $id,
        ]);

        return $stmt->fetchAll();
    }
    public function getRequirementIndicator($id)
    {
        $sql = "SELECT * FROM `indicators` WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        $requirement = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$requirement) {
            throw new Exception($translations['req_not_found'] ?? "Requirement not found.");
        }
        return $requirement;
    }

    public function editRequirementById(
        $id,
        $title,
        $description,
        $hashtags,
        $priority,
        $layer,
        $isNonFunctional,
    ) {
        $sql = "UPDATE `requirements`
                SET `title` = :title, `description` = :description, `hashtags` = :hashtags, `priority` = :priority, `layer` = :layer, `isNonFunctional` = :isNonFunctional
                WHERE `id` = :id";
        $stmp = $this->db->prepare($sql);

        $stmp->execute([
            ':title' => $title,
            ':description' => $description,
            ':hashtags' => $hashtags,
            ':priority' => $priority,
            ':layer' => $layer,
            ':isNonFunctional' => (int) $isNonFunctional,
            ':id' => $id,
        ]);

        // $updateSuccessful = $stmp->rowCount() > 0;
        // if (!$updateSuccessful) {
        //     throw new Exception($translations['requirement_update_failed'] ?? 'Requirement update failed.');
        // }
    }

    public function editIndicatorForRequirement(
        $requirementId,
        $id,
        $indicator_name,
        $unit,
        $value,
        $indicator_description
    ) {
        $sql = "UPDATE `indicators` 
                SET `indicator_name` = :indicator_name, 
                    `unit` = :unit, 
                    `value` = :value, 
                    `indicator_description` = :indicator_description 
                WHERE `id` = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(params: [
            ':id' => $id,
            ':indicator_name' => $indicator_name,
            ':unit' => $unit,
            ':value' => $value,
            ':indicator_description' => $indicator_description
        ]);

        // $updateSuccessful = $stmt->rowCount() > 0;
        // if (!$updateSuccessful) {
        //     throw new Exception($translations['requirement_update_failed'] ?? 'Requirement update failed.');
        // }
    }

    public function removeRequirementById($id)
    {
        $sql = "DELETE FROM `requirements` WHERE `id` = :id";
        $stmp = $this->db->prepare($sql);
        $stmp->execute([':id' => $id]);

        if (!$stmp->rowCount()) {
            throw new Exception($translations['requirement_deletion_failed'] ?? "Requirement deletion failed.");
        }
    }

    public function getRequirementsByFilters(
        $layer = null,
        $priority = null,
        $isNonFunctional = null
    ) {
        try {
            $sql = "SELECT r.*
                    FROM requirements r
                    WHERE 1=1";

            $params = [];

            if ($layer) {
                $sql .= " AND r.layer = :layer";
                $params[':layer'] = $layer;
            }

            if ($priority) {
                $sql .= " AND r.priority = :priority";
                $params[':priority'] = $priority;
            }

            if ($isNonFunctional !== null) {
                $sql .= " AND r.isNonFunctional = :isNonFunctional";
                $params[':isNonFunctional'] = (int) $isNonFunctional;
            }


            $sql .= " ORDER BY r.id ASC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception(($translations['error'] ?? 'Error') . ': ' . $e->getMessage());
        }
    }

    public function getAllRequirements()
    {
        $sql = "SELECT r.*, i.indicator_name, i.unit, i.value, i.indicator_description
            FROM requirements r
            LEFT JOIN indicators i ON r.id = i.requirement_id
            ORDER BY r.id ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

}