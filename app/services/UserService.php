<?php
require_once __DIR__ . '/../database/DatabaseConnection.php';
class UserService
{
    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new UserService();
        }
        return self::$instance;
    }

    private $db;
    private function __construct()
    {
        $this->db = DatabaseConnection::getInstance()->getConnection();
    }

    public function register($username, $email, $password, $user_group)
    {
        if (!empty($username) && !empty($email) && !empty($password)) {
            try {
                $sql = "INSERT INTO `users` (`username`, `password`, `email`, `user_group`) VALUES (?,?,?,?)";
                $stmp = $this->db->prepare($sql);
                $stmp->execute([$username, $password, $email, $user_group]);

                return $this->db->lastInsertId();
            } catch (PDOException $e) {
                throw new Exception("User with the provided username or email already exists");
            }
        } else {
            throw new Exception("Please fill all the fields");
        }
    }

    public function login($email, $password)
    {
        if (!empty($email) && !empty($password)) {
            try {
                $sql = "SELECT * FROM `users` WHERE `email` = :email AND `password` = :password";
                $stmp = $this->db->prepare($sql);
                $stmp->execute([
                    ':email' => $email,
                    ':password' => $password,
                ]);

                $user = $stmp->fetch();
                return $user;
            } catch (PDOException $e) {
                throw new Exception("User with the provided username or email already exists");
            }
        } else {
            throw new Exception("Please fill all the fields");
        }
    }
}

?>