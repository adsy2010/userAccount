<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 15/03/16
 * Time: 21:38
 */
session_start();
class responses extends db{
    //
    //20 could not add
    //21 does not exist
    //22 already exists
    //23 invalid data

    //get query type = user
    //nothing else then use the hashed session_id to obtain the data and return
    //

    /**
     * Database instance is created ready for further instructions
     */
    function __construct()
    {
        parent::__construct();
    }

    //Following debug functions will be removed
    public function debugExecuteSelect()
    {
        header('Content-type:text/xml');
        echo $this->get("SELECT * FROM user WHERE email=?", array("s", "adsy@00freebuild.info"));
    }

    public function debugLogoutAll()
    {
        $sql = "DELETE FROM userSession";
        $this->delete($sql);
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

    /**
     * @param string $user
     * @return mixed
     */
    public function retrieveUser($user = "")
    {
        $sql = "SELECT users.* FROM users
                INNER JOIN userSession ON user.uid = userSession.uid
                WHERE userSession.salt=? OR user.uid=?";

        $salt = (isset($_SESSION['salt'])) ? $_SESSION['salt'] : "";

        //execute select here
        return $this->get($sql, array("ss", $salt, $user));
    }

    /**
     * @param int $obj
     * @return mixed
     */
    public function retrieveUsers($obj = 0)
    {
        $sql = "SELECT email, name FROM user ORDER BY ? LIMIT ?, ?";

        return $this->get($sql, array("sii", $_GET['order'], $obj, 10));
    }

    /**
     * Method uses the salt variable stored in $_SESSION to retrieve
     * a logged in user. If the salt variable matches, the user is returned.
     *
     * @param $item
     * @return string An XML representation of the data stored for the user
     */
    public function retrieveCurrentUser($item)
    {
        $sql = "SELECT user.* FROM user INNER JOIN userSession ON user.uid = userSession.uid WHERE userSession.salt=?";
        return $this->get($sql, array("s", $item));
    }

    /**
     * Method adds a user to the database, data must be sent via
     * a form using POST. Required fields are: name, email and password
     *
     * @throws Exception
     */
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
    }

    /**
     * Method creates a friendly date string for use
     * in a MySQL database. The format is Y-m-d H:i:s and uses
     * the current time
     *
     * @return string A date time string designed for MySQL database
     * insertion
     */
    private function getCurrentDate()
    {
        return date("Y-m-d H:i:s", time());
    }

    /**
     * Method resets all logon counts for all users.
     * WARNING: This may take a while for a large database
     */
    public function resetLogonCount()
    {
        //this function is designed to reset an account and
        //set its lastOn field to the date the user registered.

        //resets all
        $sql = "UPDATE user SET logonCount=0, lastOn=regDate";

        $this->update($sql);

    }

    /**
     * Method removes logon's that are over 24 hours old
     */
    private function cleanupLogon()
    {
        $sql = "DELETE FROM userSession WHERE lastActive < NOW() - INTERVAL 24 HOUR";
        $this->delete($sql, null);
    }

    /**
     * Method logs a user out. This will terminate the current
     * session information stored
     */
    public function logout()
    {
        //TODO: Setup the full log off script
        $sql = "DELETE FROM userSession WHERE salt=?";
        $this->delete($sql, array("s", $_SESSION['salt']));
        session_destroy();
    }

    /**
     * Method logs a user in by checking user exists and setting a
     * $_SERVER variable called salt. This should work on multiple
     * domains as the $_SERVER variable will be created and maintained
     * on the database server
     *
     * @return string XML content will be served if successful else an
     * HTML error page will be generated
     * @throws Exception The exceptions generated are either no form sent
     * or login information failure
     */
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

        //jquery erroring here
        if(empty($_POST))
            throw new Exception("A login was invoked but no form was sent.", 404);

        $password = $this->generatePasswordSalt($_POST['password']);
        $salt = $this->generateSessionSalt();

        try{
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
        }
        catch(Exception $e)
        {
            //customise the error to a logon error
            throw new Exception("The logon failed. ".$_POST['email'], 403);
        }

        //return details for the logged on user. This supplies their name.
        return $this->retrieveCurrentUser($salt);
    }


}