<?php

namespace Home\Controller;
use lib\system\Controller;
use home\model\UserModel;

class UserController extends Controller {
    function index() {
        echo 999;
    }
    function getInfo() {
        $User = new UserModel ();
        $User->getInfo();
    }
}