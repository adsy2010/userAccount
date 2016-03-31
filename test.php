<?php

class test
{
    private $name, $email;

    function __construct()
    {
        $this->setNames();
    }

    function setNames(){
        $arr = array("name" => "bob", "email" => "dd@gg.com");

        foreach($arr as $key => $val)
        {
            $this->$key = $val;
            //$this->varName(compact($key));
        }

        echo "{$this->name}\r\n<br>";
        echo $this->email;
    }
}

$my_class = new test();
//var_dump( $my_class );

