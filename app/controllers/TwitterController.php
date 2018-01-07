<?php

class TwitterController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {

    	$twitterSearch = new TwitterSearchApi();
        $twitterSearch->StartTwitterSearch('word','recent');		
		$twitterSearch->SaveTopUsersToFireBase();
    }

}

