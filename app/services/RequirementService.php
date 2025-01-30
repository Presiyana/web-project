<?php
require_once __DIR__ . '/../database/DatabaseConnection.php';
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
        $indicators = [] 
    ) {
        $sql = "INSERT INTO `requirements` (`title`, `description`, `hashtags`, `priority`, `layer`, `isNonFunctional`) 
                VALUES (?,?,?,?,?,?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$title, $description, $hashtags, $priority, $layer, (int)$isNonFunctional]);
    
        $requirementId = $this->db->lastInsertId();
   
        if ($isNonFunctional && !empty($indicators)) {
            $this->addIndicatorForRequirement(
                $requirementId,
                $indicators['indicator_name'],
                $indicators['unit'],
                $indicators['value'],
                $indicators['indicator_description']
            );
        }
    
        return $requirementId;
    }

    public function addIndicatorForRequirement($requirementId, $indicator_name, $unit, $value, $indicator_description)
    {
        $indicator_name = $indicator_name ?? 'N/A';
        $unit = $unit ?? 'N/A';
        $value = $value ?? 0;
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

    public function getRequirementById($id)
    {
        // $sql = "SELECT * FROM `requirements` WHERE id = :id";
        $stmp = $this->db->prepare("
        SELECT r.id, r.title, r.description, r.priority, r.layer, r.hashtags, r.isNonFunctional,
            i.indicator_name, i.unit, i.value, i.indicator_description 
        FROM requirements r
        LEFT JOIN indicators i ON r.id = i.requirement_id
        WHERE r.id = :id
        ");
        $stmp->execute([':id' => $id]);

        $requirement = $stmp->fetch(PDO::FETCH_ASSOC);

        if (!$requirement) {
            throw new Exception('requirement not found');
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
        $isNonFunctional
    ) {
        $sql = "UPDATE `requirements` SET `title` = :title, `description` = :description, `hashtags` = :hashtags, `priority` = :priority, `layer` = :layer, `isNonFunctional` = :isNonFunctional WHERE `id` = :id";
        $stmp = $this->db->prepare($sql);
        $stmp->execute([
            ':title' => $title,
            ':description' => $description,
            ':hashtags' => $hashtags,
            ':priority' => $priority,
            ':layer' => $layer,
            ':isNonFunctional' => (int)$isNonFunctional,
            ':id' => $id,
        ]);
    }

    public function editIndicatorsForRequirement(
        $requirementId,
        $indicator_name,
        $unit,
        $value,
        $indicator_description
    ) {

        $indicator_name = $indicator_name ?? 'N/A';
        $unit = $unit ?? 'N/A';
        $value = $value ?? 0;
        $indicator_description = $indicator_description ?? 'N/A';
    
        $sql = "UPDATE `indicators` 
                SET `indicator_name` = :indicator_name, 
                    `unit` = :unit, 
                    `value` = :value, 
                    `indicator_description` = :indicator_description 
                WHERE `requirement_id` = :requirement_id";
    
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':requirement_id' => $requirementId,
            ':indicator_name' => $indicator_name,
            ':unit' => $unit,
            ':value' => $value,
            ':indicator_description' => $indicator_description
        ]);
    }

    public function removeRequirementById($id)
    {
        $sql = "DELETE FROM `requirements` WHERE `id` = :id";
        $stmp = $this->db->prepare($sql);
        $stmp->execute([':id' => $id]);
    }

}

?>