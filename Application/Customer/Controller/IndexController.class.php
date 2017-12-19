<?php
namespace Customer\Controller;
use Common\Controller\CustomerBaseController;
class IndexController extends CustomerBaseController{
    public function index(){
        redirect(U('Client/info'));
    }
}