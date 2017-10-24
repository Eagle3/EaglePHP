<?php
namespace admin\controller;
use lib\system\Controller;
use home\model\UserModel;

class UserController extends Controller {
    function index(){
        echo 444;
    }


    function getInfo(){
       $User = new User();
    	$User->getInfo();
    }
    


}