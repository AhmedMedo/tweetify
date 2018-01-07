<?php

class UsersController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
    	echo "sss";
    }

    public function loginAction(){
    	return $this->view->pick('login/index');
    }

}

