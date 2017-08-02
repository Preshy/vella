<?php

namespace App\Controllers;
use Vella\Module\Database;

class Vella {
    public function index()
    {
        Database::insert("users", array('fullname' => 'Precious Opusunju'));
        Database::update("users", array("fullname" => "Master Preshy"))->where('userid', 1);
    }
}