<?php

require_once __DIR__ . '/../root/config_root.php';

class db_config {

    private static $instance = null;
    private $connection;
    private $mongoConnection;

    private function __construct()
    {
        $cfg_model = new config_root();
        $this->connection = mysqli_connect($cfg_model->getServer(), $cfg_model->getUsername(), $cfg_model->getPassword(), $cfg_model->getDatabase());
        $this->mongoConnection = $cfg_model->getMongoConnection();
    }

    public static function getInstance()
    {
        if(!self::$instance) { self::$instance = new db_config(); }
        return self::$instance;
    }

    public function getConnection()
    {
        if (!isset($this->connection->host_info)) {
            $cfg_model = new config_root();
            $this->connection = mysqli_connect($cfg_model->getServer(), $cfg_model->getUsername(), $cfg_model->getPassword(), $cfg_model->getDatabase());
        }
        return $this->connection;
    }
    
    public function getMongoConnection()
    {
        return $this->mongoConnection;
    }
}
?>