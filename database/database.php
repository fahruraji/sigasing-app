<?php
class Database
{
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $conn;

    function __construct()
    {
        $this->host     = 'localhost';
        $this->username = 'root';
        $this->password = 'rs3l4w9';
        $this->db_name  = 'praktikum_presensi_penggajian';
    }

    public function getConnection()
    {
        try {
            $this->conn = new PDO("mysql:host=" . $this->host .
                            ";dbname=" . $this->db_name,
                            $this->username,
                            $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE,
                                      PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    } 
}
