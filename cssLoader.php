<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 21/04/16
 * Time: 22:00
 */

header("Content-Type: text/css");
echo file_get_contents("bootstrap/css/".$_GET['css']);
