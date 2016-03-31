<?php

require_once 'dbInterface.php';
require_once 'db.php';
require_once 'responses.php';

class responseHandler
{
    private $responses;

    public function __construct()
    {
        $this->responses = new responses();
        try{
            $this->section();
        }
        catch (Exception $e)
        {
            //echo $e->getMessage();
            $this->responses->debugFieldNameList("user");
        }
    }

    private function section()
    {
        if(!isset($_GET['section']))
            throw new Exception("No section selected");

        switch($_GET['section'])
        {
            case 'user':
                $this->user();
                break;

            default:

                break;
        }
    }

    private function user()
    {
        if(!isset($_GET['action']))
            return false;

        switch($_GET['action'])
        {
            case 'login':
                $this->responses->login();
                break;

            case 'register':
                $this->responses->addUser();
                break;

            case 'logout':
                $this->responses->logout();
                break;

            case 'profile':
               break;

            case 'list':
                break;

            case 'resetLogonCount':
                $this->responses->resetLogonCount();
                break;

            case 'activate':
                break;

            default:
                return false;
        }

        return true;
    }
}

new responseHandler();