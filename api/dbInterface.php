<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 15/03/16
 * Time: 21:08
 */

interface dbInterface {

    public function get();
    public function delete();
    public function update();
    public function create();
    public function status();

} 