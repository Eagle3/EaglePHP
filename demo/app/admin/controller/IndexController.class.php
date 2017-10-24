<?php
namespace admin\controller;
use lib\system\Controller;
class IndexController extends Controller {
    public function index(){
    	pr($_GET,'admin\controller\index');
    }

}