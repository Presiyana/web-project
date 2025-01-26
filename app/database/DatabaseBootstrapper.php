<?php
class DatabaseBootstrapper
{
    private $conn;
    private $schemaFile;

    public function __construct($connection)
    {
        $this->conn = $connection;
        $this->schemaFile = __DIR__ . '/../../sql/schema.sql';
    }

    public function initialize()
    {
        if ($this->needsInitialization()) {
            $this->createDatabase();
            $this->createTables();
        }
    }

    private function needsInitialization()
    {
        try {
            $this->conn->exec("USE " . DB_NAME);
            return false;
        } catch (PDOException $e) {
            return true;
        }
    }

    private function createDatabase()
    {
        $this->conn->exec(
            "CREATE DATABASE " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci"
        );
        $this->conn->exec("USE " . DB_NAME);
    }

    private function createTables()
    {
        $sql = file_get_contents($this->schemaFile);
        $this->conn->exec($sql);
    }
}