<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 31/03/16
 * Time: 22:54
 */

session_start();
include 'model/user.php';

$u = new user();
echo $u->getName() . "<br>";
echo "My email address is {$u->getEmail()} <br>";
echo "I have logged on {$u->getLogonCount()} times
        most recently at {$u->getLastOn()}. <br>";
