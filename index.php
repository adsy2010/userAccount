<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 31/03/16
 * Time: 22:54
 */

session_start();
include 'model/user.php';
include 'model/currentUser.php';

$page = file_get_contents("tpl/main.tpl");

$u = new currentUser();

$username = "Welcome {$u->getName()} ";
$page = str_replace("{SHOW_LOGON}",
                    (empty($u->getRegDate()))?"collapsed":"collapse",
                    $page);

if(!empty($u->getRegDate())){
    $content = "My email address is {$u->getEmail()} <br>";
    $content .= "I have logged on {$u->getLogonCount()} times
            most recently at {$u->getLastOn()}. <br>";
}
$page = str_replace("{USER}", $username, $page);
$page = str_replace("{ALL}", $content, $page);
echo $page;
