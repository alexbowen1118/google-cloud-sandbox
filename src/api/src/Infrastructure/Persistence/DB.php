<?php
namespace DPR\API\Infrastructure\Persistence;

use \PDO;


// Deprecated!! DO NOT USE
class DB {
    private $host;
    private $user;
    private $pass;
    private $dbname;

    public function __construct()
    {
        $this->host = getenv('DB_HOST');
        $this->user = getenv('DB_USER');
        $this->pass = getenv('DB_PASSWORD');
        $this->dbname = 'dprcal_new';
    }

    public function connect()
    {
        $conn_str = "mysql:host=$this->host;dbname=$this->dbname";
        $conn = new PDO($conn_str, $this->user, $this->pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }
}
