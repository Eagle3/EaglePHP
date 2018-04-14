<?php

namespace admin\controller;

use lib\system\Controller;

class Index extends Controller {
    public function index() {
        pr( $_GET, 'admin\controller\index' );
    }
    public function index2() {
        echo 'admin\controller\index2';
    }
}