<?php
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Phalcon\Queue\Beanstalk;
class DemoController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
    	
    	$client = new MongoDB\Client;
		$tweets = $client->tweets_db;
		$result1 = $tweets->users->find([],
			[
				
				'limit' =>2 ,
				'sort'   =>['followers_count' =>-1],
				['projection' =>['user_id' => 1,'_id' => 0]]	

		  ]);

		foreach ($result1 as  $doc) {
        var_dump($doc);
    }	
		
	
				
	}

}

