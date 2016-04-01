<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 15/03/16
 * Time: 21:08
 */

interface dbInterface {

    public function get($sql, $data=array());
    public function delete($sql, $data=array());
    public function update($sql, $data=array());
    public function create($sql, $data=array());

} 