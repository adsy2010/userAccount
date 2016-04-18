<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 13/03/16
 * Time: 20:53
 */
session_start();
class user {

    private $name, $email, $lastOn, $regDate, $logonCount;

    /* @var userSession $session */
    private $session;

    /* @var userDetail[] $userDetails */
    private $userDetails = array();

    public function __construct()
    {
        //if(!file_exists("/api/user/1/")) echo "hello";
        try{

            $url = "http://{$_SERVER['SERVER_NAME']}/api/user/1/{$_SESSION['salt']}";
            $xml = new SimpleXMLElement($url, 0, true);

            foreach($xml->row->children() as $key => $val)
                $this->$key = $val;

        }
        catch(Exception $e)
        {
            echo $e->getMessage() . "<br>";
            echo $e->getLine();
        }

        //echo "1".file_get_contents("/api/user/1/");
        //print_r($xml);
        //echo session_id();
        //echo $_SESSION['salt'];

        //$arr = json_decode(file_get_contents("db.php?method=get&table=user"), true);

    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $lastOn
     */
    public function setLastOn($lastOn)
    {
        $this->lastOn = $lastOn;
    }

    /**
     * @return mixed
     */
    public function getLastOn()
    {
        return $this->lastOn;
    }

    /**
     * @param mixed $logonCount
     */
    public function setLogonCount($logonCount)
    {
        $this->logonCount = $logonCount;
    }

    /**
     * @return mixed
     */
    public function getLogonCount()
    {
        return $this->logonCount;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $regDate
     */
    public function setRegDate($regDate)
    {
        $this->regDate = $regDate;
    }

    /**
     * @return mixed
     */
    public function getRegDate()
    {
        return $this->regDate;
    }

    /**
     * @param userSession $session
     */
    public function setSession($session)
    {
        $this->session = $session;
    }

    /**
     * @return userSession
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param userDetail[] $userDetails
     */
    public function setUserDetails($userDetails)
    {
        $this->userDetails = $userDetails;
    }

    /**
     * @return userDetail[]
     */
    public function getUserDetails()
    {
        return $this->userDetails;
    }

} 