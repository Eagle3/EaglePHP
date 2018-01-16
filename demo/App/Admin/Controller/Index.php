<?php

namespace Admin\Controller;

use Lib\System\Controller;

class IndexController extends Controller {
    public function index() {
        pr( $_GET, 'admin\controller\index' );
    }
    public function index2() {
        echo 'admin\controller\index2';
    }
}