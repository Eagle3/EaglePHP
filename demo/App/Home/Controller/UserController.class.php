<?php

namespace Home\Controller;
use Lib\System\Controller;
use Home\Model\UserModel;

class UserController extends Controller {
    function index() {
        echo 999;
    }
    function getInfo() {
        $User = new UserModel ();
        $User->getInfo();
    }
}