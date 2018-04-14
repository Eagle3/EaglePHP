<?php

namespace admin\controller;

use lib\system\Controller;


class UserController extends Controller {
    function index() {
        echo 444;
    }
    function getInfo() {
        $User = new User();
        $User->getInfo();
    }
}