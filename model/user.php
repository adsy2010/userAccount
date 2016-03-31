<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 13/03/16
 * Time: 20:53
 */

class user {

    private $name, $email, $lastOn, $regDate, $logonCount;

    /* @var userSession $session */
    private $session;

    /* @var userDetail[] $userDetails */
    private $userDetails = array();

    public function __construct()
    {
        $arr = json_decode(file_get_contents("db.php?method=get&table=user"), true);
        foreach($arr as $key => $val)
        {
            $this->$key = $val;
        }
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