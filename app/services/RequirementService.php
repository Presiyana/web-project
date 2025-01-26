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
        $stmt = $this->db->query("SELECT * from `requirements`");
        $result = $stmt->fetchAll();
        return $result;
    }

    public function addRequirement(
        $title,
        $description,
        $hashtags,
        $priority,
        $layer
    ) {
        $sql = "INSERT INTO `requirements` (`title`, `description`, `hashtags`, `priority`, `layer`) VALUES (?,?,?,?,?)";
        $stmp = $this->db->prepare($sql);
        $stmp->execute([$title, $description, $hashtags, $priority, $layer]);

        return $this->db->lastInsertId();
    }

    public function getRequirementById($id)
    {

        $sql = "SELECT * FROM `requirements` WHERE id = :id";
        $stmp = $this->db->prepare($sql);
        $stmp->execute([':id' => $id]);

        $requirement = $stmp->fetch();

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
        $layer
    ) {
        $sql = "UPDATE `requirements` SET `title` = :title, `description` = :description, `hashtags` = :hashtags, `priority` = :priority, `layer` = :layer WHERE `id` = :id";
        $stmp = $this->db->prepare($sql);
        $stmp->execute([
            ':title' => $title,
            ':description' => $description,
            ':hashtags' => $hashtags,
            ':priority' => $priority,
            ':layer' => $layer,
            ':id' => $id,
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