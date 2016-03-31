<?php

require_once 'dbInterface.php';
require_once 'db.php';
require_once 'responses.php';


$x = new responses();

function users(responses $x)
{
    switch($_GET['action'])
    {
        case 'register':
            $x->addUser();
            break;
    }
}

try{
    //$x->debugTableList();
    //$x->debugFieldNameList("user");#
    switch($_GET['section'])
    {
        case 'user':
            users($x);
            break;
    }
    $x->login();
    //print_r($_GET);
}
catch (Exception $e)
{
    echo $e->getMessage();
}

if(isset($_GET['table']))
{
    switch($_GET['table'])
    {
        case 'user':
            break;
        case 'group':
            break;
    }
}