<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 15/03/16
 * Time: 21:08
 */

class db implements dbInterface{

    /* @var mysqli $database */
    private $database;
    private $salt;

    /**
     * @return \mysqli
     */
    protected function getDatabase()
    {
        return $this->database;
    }

    public function __construct()
    {
        $data = parse_ini_file("config.ini");
        $this->salt = $data['salt'];
        $this->database = new mysqli($data['host'], $data['username'], $data['password'], $data['database']);
        //echo "Connected using {$data['username']}.<br>";
    }

    public function get()
    {
        // TODO: Implement get() method.
        //preg_match selects only valid table names and removes all the other crap.
        //This defends against database attacks
        preg_match("/[a-zA-Z_]+/", $_GET['table'], $output_array);
        $table = $output_array[0];
    }

    public function delete()
    {
        // TODO: Implement delete() method.
    }

    public function update()
    {
        // TODO: Implement update() method.
    }

    public function create()
    {
        // TODO: Implement create() method.
    }

    /**
     * Returns the status of the database call if not successful
     */
    public function status()
    {
        // TODO: Implement status() method.
    }

    public function getSalt()
    {
        return $this->salt;
    }
}