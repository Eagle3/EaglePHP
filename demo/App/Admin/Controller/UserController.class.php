<?php
namespace Admin\Controller;
use lib\system\Controller;
use Home\mModel\UserModel;

class UserController extends Controller {
    function index(){
        echo 444;
    }


    function getInfo(){
       $User = new User();
    	$User->getInfo();
    }
    


}