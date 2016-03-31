<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 13/03/16
 * Time: 20:54
 */

class userSession {

    private $uid, $salt, $lastActive, $response;

    public function __construct()
    {

    }

    public function setLastActive($lastActive)
    {
        $this->lastActive = $lastActive;
    }

    public function setResponse($response)
    {
        $this->response = $response;
    }

    private function updateLastActive()
    {
        $this->setResponse(file_get_contents('db.php?method=update&table=session&lastActive=now'));
    }

    public function getLastActive($uid)
    {
        return $this->lastActive;
    }

    private function terminateSession()
    {
        $this->setResponse(file_get_contents("db.php?method=delete&table=session&salt={$this->salt}"));
    }

    private function createSession($uid = 0)
    {
        $salt = hash("MD5", session_id());
        $this->setResponse(file_get_contents("db.php?table=session&salt={$salt}&uid={$uid}"));
    }

} 