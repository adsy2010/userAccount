<?php
session_start();
require_once 'dbInterface.php';
require_once 'db.php';
require_once 'responses.php';

class responseHandler
{
    private $responses;

    private $section, $action, $item;

    public function __construct()
    {
        $this->responses = new responses();

        $this->section = (isset($_GET['section'])) ? $_GET['section'] : null;
        $this->action = (isset($_GET['action'])) ? $_GET['action'] : null;
        $this->item = (isset($_GET['obj'])) ? $_GET['obj'] : null;


        try{
            if($this->section == null || $this->action == null)
                throw new Exception("Not enough data", 404);

            $data = $this->section();

            echo $this->respond(200, $data, "text/xml");

        }
        catch (Exception $e)
        {
            //echo $e->getMessage();
            //$this->responses->debugExecuteSelect();
            //$this->responses->debugFieldNameList("user");
            echo $this->respond($e->getCode(), $e->getMessage(), "text/html");
            //header("Content-Type: text/xml");
            //echo $this->responses->retrieveUsers();
            //echo 'test';
        }
    }

    /**
     * Respond with the page code, 200 for all clear
     * Set the type of content if it isn't automatically determinable
     * such as XML (text/xml)
     *
     * @param int    $code
     * @param mixed  $data
     * @param string $type
     * @return string
     */
    private function respond($code = 200, $data, $type = null)
    {

        ($type != null) ? header("Content-Type: {$type}") : null;
        http_response_code($code);
        return $data;
    }

    private function section()
    {
        switch($this->section)
        {
            case 'user':
                return $this->user();
                break;

            case 'auth':
                //Check authorisation then run _GET['url'] ????
                return $this->responses->retrieveCurrentUser($this->item);

            default:
                return false;
                break;
        }
    }

    private function user()
    {
        $data = null;

        //is authorisation needed?

        $auth = $_GET['auth']; //This is a public key and can be authorised with the secret key (users password)

        switch($this->action)
        {
            case 'login':
                $data = $this->responses->login();
                break;

            case 'register':
                $this->responses->addUser();
                break;

            case 'logoutAll':
                $this->responses->debugLogoutAll();
                break;

            case 'logout':
                $this->responses->logout();
                break;

            case 'profile':
                (!empty($this->item)) ? $this->responses->retrieveUser($this->item) : $this->responses->retrieveUser($auth);
               break;

            case 1:
                $data = $this->responses->retrieveCurrentUser($auth);
                break;

            case 'list':

                break;

            case 'resetLogonCount':
                $this->responses->resetLogonCount($this->item);
                break;

            case 'activate':

                break;

        }

        return $data;
    }
}

new responseHandler();