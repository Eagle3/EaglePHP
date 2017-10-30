<?php
namespace Admin\Controller;
use lib\system\Controller;
class IndexController extends Controller {
    public function index(){
    	pr($_GET,'admin\controller\index');
    }
    
    public function index2(){
        echo 'admin\controller\index2';
    }
    
}