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

    public function generateSessionSalt()
    {
        return hash("MD5", session_id() . time());
    }

    public function executeSelect($sql, $data = array())
    {
        if(!($stmt = $this->getDatabase()->prepare($sql)))
            throw new Exception("Failed to prepare the query");

        if(!empty($data))
        {
            $tmp = array();
            foreach($data as $key => $value) $tmp[$key] = &$data[$key];

            if(!(call_user_func_array(array($stmt, 'bind_param'), $tmp)))
                throw new Exception("Failed to bind parameters");
        }

        if(!($stmt->execute()))
            throw new Exception("Failed to execute query");


        $stmt->store_result();

        //this is the part that differs from execute()

        $metadata = $stmt->result_metadata();
        $outData = array();

        $cols = $metadata->fetch_fields();

        foreach($cols as $col)
            $outData[] = &$results[$col->name];

        if(!empty($outData))
            call_user_func_array(array($stmt, 'bind_result'), $outData);

        //now create the xml for output
        while($stmt->fetch())
        {
            if(!isset($results))
                throw new Exception("There are no result fields to display.");

            foreach($results as $key => $val)
            {
                //$key is the name of the field
                //$val is the value of the field
            }


        }

        $stmt->close();

    }

    /**
     * Works for insert, update and delete queries.
     *
     * @param string $sql The SQL string to be executed
     * @param array  $data The data to be prepared by the SQL statement
     * Format used: "s" => "data value"
     * @throws Exception The error raised by invalid or bad data
     * @return int the number of affected rows by the query
     */
    public function execute($sql, $data = array())
    {
        if(!($stmt = $this->getDatabase()->prepare($sql)))
            throw new Exception("Failed to prepare the query");

        if(!empty($data))
        {
            $tmp = array();
            foreach($data as $key => $value) $tmp[$key] = &$data[$key];

            if(!(call_user_func_array(array($stmt, 'bind_param'), $tmp)))
                throw new Exception("Failed to bind parameters");
        }

        if(!($stmt->execute()))
            throw new Exception("Failed to execute query");


        $stmt->store_result();

        if(!($this->getDatabase()->affected_rows >= 1))
            throw new Exception("The database could not be changed.",20);

        $stmt->close();

        return $this->getDatabase()->affected_rows;
    }
}