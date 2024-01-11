<?php

namespace App\DB;

use PDOException;
use PDO;

class MySQL
{
    /**
     * @var string DB_HOST
     */
    const DB_HOST = 'localhost';

    /**
     * @var string DB_NAME
     */
    const DB_NAME = 'db';

    /**
     * @var string DB_USER
     */
    const DB_USER = 'root';

    /**
     * @var string DB_PASS
     */
    const DB_PASS = 'root';

    /**
     * @var object $db
     */
    private $db;

    public function __construct()
    {
        $this->db = $this->setDB();
    }

    /**
     * Set connection with database
     * @return PDO
     */
    public function setDB(): PDO
    {
        try {
            return new PDO("mysql:host=".self::DB_HOST.";dbname=".self::DB_NAME, self::DB_USER, self::DB_PASS);
        }catch(PDOException $e) {
            die("ERROR: ".$e->getMessage());
        }
    }
    
    /**
     * Get connection with database
     */
    public function getDB()
    {
        return $this->db;
    }
}