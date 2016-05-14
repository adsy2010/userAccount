<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 21/04/16
 * Time: 17:21
 */

class currentUser extends user {


    public function __construct()
    {
        try{

            $url = "http://{$_SERVER['SERVER_NAME']}/api/user/1/?auth={$_SESSION['salt']}";

            $xml = new SimpleXMLElement($url, 0, true);

            if(($xml->attributes()->rowCount) == "1")
            {
                foreach($xml->row->children() as $key => $val)
                {
                    $key = "set" . ucfirst($key); //use parent setter methods
                    if(method_exists($this, $key))
                        $this->$key($val);
                }
            }
            else
            {
                $this->setName("Guest");
            }



        }
        catch(Exception $e)
        {
            echo "Error Message: " . $e->getMessage() . "<br>";
            echo "Error on line " . $e->getLine();
            echo " in file " . $e->getFile();
            throw new Exception("Data could not be retrieved for user.", 401);
        }
    }

} 