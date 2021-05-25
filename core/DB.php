<?php

namespace Core;

class DB {

    private $conn = null;
    private $stmt = null;
    
    public function __construct() {
      try {

        $servername = $_ENV['DB_SERVER'];
        $username = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASSWORD'];
        $database = $_ENV['DB_NAME'];

        $this->conn = new \PDO("mysql:host=$servername;dbname=$database", $username, $password);
        // set the PDO error mode to exception
        $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        
      } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
      }
    }

    public function prepare($query)
    {
      $this->stmt = $this->conn->prepare($query);
      return $this;
    }

    public function execute($data)
    {
      return $this->stmt->execute($data);
    }

    public function delete($query)
    {
      return $this->conn->exec($query);
    }

    public function fetch($query)
    {
      return $this->conn->query($query)->fetchAll(\PDO::FETCH_ASSOC);
    }

    

}



