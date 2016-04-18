<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 15/03/16
 * Time: 21:08
 */
session_start();
class db implements dbInterface{

    /* @var mysqli $database */
    private $database;
    private $salt;
    private $status;

    /**
     * @return mysqli
     */
    protected function getDatabase()
    {
        return $this->database;
    }

    public function __construct()
    {
        $data = parse_ini_file("config.ini");
        $this->salt = $data['salt']; //If this is changed then ALL passwords will fail
        $this->database = new mysqli($data['host'], $data['username'], $data['password'], $data['database']);
        $this->setStatus();
    }


    /**
     * @param       $sql
     * @param array $data
     * @return mixed
     */
    public function get($sql, $data = array())
    {
        return $this->executeSelect($sql,$data);
    }

    /**
     * @param       $sql
     * @param array $data
     * @return int
     */
    public function delete($sql, $data = array())
    {
        return $this->execute($sql,$data);
    }

    /**
     * @param       $sql
     * @param array $data
     * @return int
     */
    public function update($sql, $data = array())
    {
        return $this->execute($sql,$data);
    }

    /**
     * @param       $sql
     * @param array $data
     * @return int
     */
    public function create($sql, $data = array())
    {
        return $this->execute($sql,$data);
    }



    /**
     * @param int $int an integer setting the response code
     */
    private function setStatus($int = 200)
    {
        $this->status = $int;
    }

    /**
     * Returns the status of the database call if not successful
     *
     * @return int $status Returns the status code of the response
     */
    function getStatus()
    {
        return $this->status;
    }


    /**
     * @return string $salt Returns an unhashed key used for hashing values
     */
    protected function getSalt()
    {
        return $this->salt;
    }

    public function generateSessionSalt()
    {
        return hash("MD5", session_id() . time());
    }

    protected function generatePasswordSalt($key)
    {
        return hash("MD5",  $this->getSalt() . $key);
    }

    private function executeSelect($sql, $data = array())
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
        $x = new SimpleXMLElement("output.xml", 0, true);

        $x->attributes()->status = $this->getStatus();

        $x->attributes()->rowCount = "{$stmt->num_rows}";

        if(!isset($results))
            throw new Exception("There are no result fields to display.");

        while($stmt->fetch())
        {
            $y = $x->addChild("row");
            foreach($results as $key => $val)
            {
                //$key is the name of the field
                //$val is the value of the field
               $y->addChild($key, $val);
            }


        }

        $stmt->close();

        return $x->asXML();
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
    private function execute($sql, $data = array())
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