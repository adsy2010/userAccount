<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 15/03/16
 * Time: 21:38
 */

class responses extends db{

    //20 could not add
    //21 does not exist
    //22 already exists
    //23 invalid data

    //get query type = user
    //nothing else then use the hashed session_id to obtain the data and return
    //

    function __construct()
    {
        parent::__construct();
    }

    public function debugExecuteSelect()
    {
        header('Content-type:text/xml');
        echo $this->get("SELECT * FROM user WHERE email=?", array("s", "adsy@00freebuild.info"));
    }

    public function debugFieldNameList($table)
    {
        $sql = "SELECT * FROM {$table} LIMIT 1";
        header('Content-type:text/xml');
        $xml = new SimpleXMLElement("data.xml",0,true);

        if(!($stmt = $this->getDatabase()->query($sql)))
            throw new Exception("Failed to run query {$sql}");
        $fieldInfo = $stmt->fetch_fields();

        foreach($fieldInfo as $field)
        {
            $f = $xml->addChild("FieldInfo");
            $f->addChild("name", $field->name);
            $f->addChild("table", $field->table);
            $f->addChild("maxlength", $field->max_length);
            $f->addChild("length", $field->length);
            $f->addChild("charsetnr", $field->charsetnr);
            $f->addChild("flags", $field->flags);
            $f->addChild("type", $field->type);

        }

        print($xml->asXML());
        $stmt->close();
    }

    public function debugTableList()
    {
        if(!($stmt = $this->getDatabase()->prepare("SHOW TABLES")))
            throw new Exception("Could not prepare show tables query");

        if(!($stmt->execute()))
            throw new Exception("Could not run query");

        $stmt->store_result();

        $stmt->bind_result($name);
        while($stmt->fetch())
        {
            echo $name . "<br>";

        }
        $stmt->close();

    }

    public function retrieveUser($user = "")
    {
        $sql = "SELECT users.* FROM users
                INNER JOIN userSession ON user.uid = userSession.uid
                WHERE userSession.salt=? OR user.uid=?";

        $salt = (isset($_SESSION['salt'])) ? $_SESSION['salt'] : "";

        //execute select here
        return $this->get($sql, array("ss", $salt, $user));

        /*if(!($result = $this->getDatabase()->prepare($sql))) throw new Exception("Query failed to prepare");
        if(!($result->bind_param("s", $_SESSION['salt']))) throw new Exception("Query failed to bind parameters");
        if(!($result->execute())) throw new Exception("Query failed to execute");
        $result->store_result();
        if(!($data = $result->fetch())) throw new Exception("Data could not be captured.");
        $result->free_result();
        $result->close();*/

    }

    private function retrieveCurrentUser()
    {
        $salt = hash("MD5", session_id());
        $sql = "SELECT users.* FROM users INNER JOIN userSession ON users.uid = userSession.uid WHERE userSession.salt={$salt}";

        if(!($result = $this->getDatabase()->prepare($sql))) throw new Exception("Query failed to prepare");
        if(!($result->execute())) throw new Exception("Query failed to execute");
        $result->store_result();
        if(!($data = $result->fetch())) throw new Exception("Data could not be captured.");
        $result->free_result();
        $result->close();
    }

    public function addUser()
    {
        $sql = "INSERT INTO user (name, email, regDate, password) VALUES (?,?,?,?)";

        /*
        if(!($stmt = $this->getDatabase()->prepare($sql)))
            throw new Exception("Could not prepare statement.");*/

        $name = htmlspecialchars($_POST['name']);
        $email = (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) ? $_POST['email'] : false;
        //$regDate = date('Y-m-d H:i:s',time());
        $password = $this->generatePasswordSalt($_POST['password']);
        //hash("MD5", $this->getSalt() . $_POST['password']);

        $checks = array("name", "email", "password");

        //The empty check
        foreach($checks as $item)
            if(empty($$item))
                throw new Exception("Data for field {$item} is missing.", 23);

        echo "{$name}<br>";
        echo "{$email}<br>";
        echo "{$this->getCurrentDate()}<br>";
        echo "{$password}<br>";

        $this->create($sql, array("ssss", $name, $email, $this->getCurrentDate(), $password));
        /*
        if(!($stmt->bind_param("ssss", $name, $email, $regDate, $password)))
            throw new Exception("Could not bind values");

        if(!($stmt->execute()))
            throw new Exception("Could not add user to the database.", 20);
        */
        /*$stmt->store_result();
        if(!($this->getDatabase()->affected_rows == 1))
            throw new Exception("The data could not be added to the database.",20);
        $stmt->close();*/
    }

    private function getCurrentDate()
    {
        return date("Y-m-d H:i:s", time());
    }

    public function resetLogonCount()
    {
        //this function is designed to reset an account and
        //set its lastOn field to the date the user registered.

        //resets all
        $sql = "UPDATE user SET logonCount=0, lastOn=regDate";

        $this->update($sql);

    }

    public function logout()
    {
        $sql = "UPDATE userSession SET salt='' WHERE salt=?";
    }

    public function login()
    {
        $sql = "UPDATE user AS tmp SET logonCount=logonCount + 1, lastOn = ?
        WHERE EXISTS
        (
        SELECT uid FROM
            (
            SELECT 1 FROM user WHERE email=? AND password=?
            ) AS tmpTable
        )";
        $sqlSession = "INSERT INTO userSession (uid, salt, lastActive)
                       SELECT uid, ?,? FROM user WHERE email=?";

        $password = $this->generatePasswordSalt($_POST['password']);
        $salt = $this->generateSessionSalt();

        $this->update($sql, array
        (
            "sss",
            $this->getCurrentDate(),
            $_POST['email'],
            $password
        ));
        $this->create($sqlSession, array(
           "sss",
            $salt,
            $this->getCurrentDate(),
            $_POST['email']
        ));

        $_SESSION['salt'] = $salt;

        return true;
    }


}