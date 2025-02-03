<?php
require_once __DIR__ . '/../database/DatabaseConnection.php';
class TaskService
{
    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new TaskService();
        }
        return self::$instance;
    }

    private $db;
    private function __construct()
    {
        $this->db = DatabaseConnection::getInstance()->getConnection();
    }

    public function getTasks()
    {
        $sql = "SELECT t.*, u.email
                FROM `tasks` t
                LEFT JOIN `users` u ON t.user_id = u.id";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetchAll();
        return $result;
    }

    public function getTaskById($id)
    {
        $sql = "SELECT t.*, u.email
                FROM `tasks` t
                LEFT JOIN `users` u ON t.user_id = u.id
                WHERE t.id = :id";
        $stmp = $this->db->prepare($sql);
        $stmp->execute([':id' => $id]);

        $task = $stmp->fetch(PDO::FETCH_ASSOC);

        if (!$task) {
            throw new Exception('task not found');
        }
        return $task;
    }

    public function addTask(
        $title,
        $user_group,
        $user_id,
    ) {
        $sql = "INSERT INTO `tasks` (`title`, `user_group`, `user_id`) 
                VALUES (?,?,?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$title, $user_group, $user_id]);

        $taskId = $this->db->lastInsertId();

        return $taskId;
    }

    public function editTask(
        $id,
        $title,
        $user_group,
    ) {
        $sql = "UPDATE `tasks`
                SET `title` = :title, `user_group` = :user_group
                WHERE `id` = :id";
        $stmp = $this->db->prepare($sql);
        $stmp->execute([
            ':title' => $title,
            ':user_group' => $user_group,
            ':id' => $id,
        ]);
    }

    public function getTaskRequirements($id)
    {
        $sql = "SELECT * FROM `taskRequirements` WHERE task_id = " . $id;
        $stmt = $this->db->query($sql);
        $result = $stmt->fetchAll();
        return $result;
    }

    public function getTaskRequirementsWithRequirementData($id)
    {
        $sql = "SELECT tr.*, r.title
                FROM `taskRequirements` tr
                LEFT JOIN `requirements` r ON r.id = tr.requirement_id
                WHERE task_id = " . $id;
        $stmt = $this->db->query($sql);
        $result = $stmt->fetchAll();
        return $result;
    }

    public function addTaskRequirement(
        $id,
        $requirement_id
    ) {
        $sql = "SELECT tr.*
                FROM `taskRequirements` tr
                WHERE tr.task_id = :id AND tr.requirement_id = :requirement_id";
        $stmp = $this->db->prepare($sql);
        $stmp->execute([':id' => $id, ':requirement_id' => $requirement_id]);

        $taskRequirement = $stmp->fetch();

        if ($taskRequirement) {
            die();
        } else {
            $sql = "INSERT INTO `taskRequirements` (`task_id`, `requirement_id`) 
                VALUES (?,?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id, $requirement_id]);
        }
    }
    public function toggleTaskRequirementCompletion(
        $task_id,
        $requirement_id
    ) {
        $sql = "SELECT tr.*
                FROM `taskRequirements` tr
                WHERE tr.task_id = :id AND tr.requirement_id = :requirement_id";
        $stmp = $this->db->prepare($sql);
        $stmp->execute([':id' => $task_id, ':requirement_id' => $requirement_id]);

        $taskRequirement = $stmp->fetch();

        if ($taskRequirement) {
            $newStatus = $taskRequirement["status"] === "complete" ? "in_progress" : "complete";
            $sql = "UPDATE `taskRequirements`
                SET `status` = :newStatus
                WHERE task_id = :task_id AND requirement_id = :requirement_id";
            $stmp = $this->db->prepare($sql);
            $stmp->execute([
                ':newStatus' => $newStatus,
                ':task_id' => $task_id,
                ':requirement_id' => $requirement_id
            ]);
        } else {
            die();
        }
    }
}

?>