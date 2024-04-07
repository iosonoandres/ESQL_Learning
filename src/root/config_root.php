<?php
//config_root.php
class config_root
{
    private $db_server;
    private $db_username;
    private $db_password;
    private $db_database;
    private $db_mongoconnection;

    public function __construct() {
        $json = file_get_contents(__DIR__."\config.json");
        $json_data = json_decode($json);

        $this->db_server = $json_data->db_server;
        $this->db_username = $json_data->db_username;
        $this->db_password = $json_data->db_password;
        $this->db_database = $json_data->db_database;
        $this->db_mongoconnection = $json_data->db_mongoconnection;
    }

    public function getServer() {
        return $this->db_server;
    }

    public function getUsername() {
        return $this->db_username;
    }

    public function getPassword() {
        return $this->db_password;
    }

    public function getDatabase() {
        return $this->db_database;
    }
    
    public function getMongoConnection() {
        return $this->db_mongoconnection;
    }

}

?>