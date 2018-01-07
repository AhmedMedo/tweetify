<?php 
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
/**
 *	TwitterSearchApi : Simple php Class to get top and recent tweets 
 *   according to giving keyword
 *@author Ahmed Alaa
 *
 */



/**
* 
*/
class TwitterSearchApi
{

	/**
     * Preform twitter Search with given keyword and search type ['recent' or poular]
     *@param $query (string) related to search word
     *@param $search_type (string) 'recent' , popular or mixed
     *@return int
     * 
     */
    public function StartTwitterSearch($query,$search_type)
    {
        $max_id = 0;
        $i = 0 ; 
        $tweets_found=0; 
        $search_query=['q' =>$query , 'result_type' =>$search_type,'count' => 100];   
        $connection = $this->PrepareConnection(); 
        $tweets_collections=$this->MongoDBInitialise()->tweets;
        $user_collections=$this->MongoDBInitialise()->users;
        //loop to call the api again to load more results         
        while ($i < 100) {
            sleep(1);
            if ($max_id == 0) {
               $this->TalkToTwitterSearchApi($connection,$search_query);
            // Repeated API call
            } else {
                // Collect older tweets using max_id in the search query to get more tweets
                --$max_id;
                $search_query['max_id'] = $max_id;
                $this->TalkToTwitterSearchApi($connection,$search_query);
            }           
            // Exit on error
          if ($connection->response['code'] != 200) {
              print "Exited with error: " . $connection->response['code'] . "<br>";
                break;            
            } 
            // Process each tweet returned
            $results = json_decode($connection->response['response']);
            $tweets = $results->statuses;
          //Save Tweets data and users to MongoDB
          foreach($tweets as $tweet) {
             ++$tweets_found;
             $max_id=$tweet->id;
             //save tweets info
            $this->SaveTweetsDataToMongoDB($tweet,$tweets_collections);
             //save user info
            $this->SaveUsersDataToMongoDb($tweet,$user_collections);
            } 
         $i++;
         
        }
        return $tweets_found;
    }
    /**
     * Configure the settings required to talk to twitter search api
     *
     *@return object
     * 
     */
    private function PrepareConnection(){
        $config = new \Phalcon\Config\Adapter\Ini(BASE_PATH.'/twitter.ini');
        return new tmhOAuth(array(
            'user_token' => $config->twitter->user_token,
            'user_secret' => $config->twitter->user_secret,
            'consumer_key' => $config->twitter->consumer_key,
            'consumer_secret' => $config->twitter->consumer_secret
                )); 
    }
    /**
     * Send Get request with search parameters to twitter
     * this will make the url like @ https://api.twitter.com/1.1/search/tweets.json?q=obama&result_type=recent&count=100
     *@param $connection (object)
     *@param $search_query(array) with search query and seach type
     *@return int
     * 
     */
    private function TalkToTwitterSearchApi($connection,$search_query=[]){

       return   $connection->request('GET', $connection->url('1.1/search/tweets'),$search_query);                   
    }
    /**
     * Save Top 20 users with highest follwers to firebase
     *
     *@return $staues (string) with success or not
     * 
     */
    public function SaveTopUsersToFireBase(){
        $tweets_db=$this->MongoDBInitialise();
        $top_users_collection = $tweets_db->users->find([],
            [
                'limit' =>20 ,
                'sort'   =>['followers_count' =>-1]

          ]);      
      $top_users=$this->MongoCollectionToArray($top_users_collection);
      $status=$this->PushUsersDataToFireBase($top_users);    
      return $status;
    }
     /**
     * Convert a collection data to array to upload it to firebase
     *@param $mongo_collection_data (MongoObject) collectio on mongo data
     *@return $mongo_data_array (array) of users data
     * 
     */
    private function MongoCollectionToArray($mongo_collection_data){
        $data=[];
        $mongo_data_array=[];
        foreach ($mongo_collection_data as $single_document) {
            
                foreach ($single_document as $key => $value) {
                    if(is_object($value))
                    {   //convert the _id object to string to store it
                        $value=(string)$value;
                    }
                    $data[$key] = $value;
                }
                array_push($mongo_data_array,$data);
        }
       return $mongo_data_array;
    }
     /**
     * Upload Top 20 users to firebase
     *@param $data_to_save (array)
     *@return $message (string) to indicate statues of uploading
     * 
     */
    private function PushUsersDataToFireBase($data_to_save){
         $serviceAccount = ServiceAccount::fromJsonFile(APP_PATH.'/firebase/firebase.json');
        $apikey='AIzaSyB7tE9inqqAXZudqpw_4K17RUCswb_AU-o';
        try {
             $firebase = (new Factory)
            ->withServiceAccountAndApiKey($serviceAccount, $apikey)
            ->withDatabaseUri('https://tweetify-app.firebaseio.com/')
            ->create();
            $database = $firebase->getDatabase();
            $newPost = $database->getReference('top_users')->push($data_to_save);
            $message = "Users Data successfuly uploaded";
            return $message;
            
        } catch (\Exception $e) {
            $meessage = 'Users Data failed to upload';
            return $message;     
        }

    }
     /**
     * Connect to MongoDB Client
     *
     *@return $tweets_database (object)
     * 
     */
    private function MongoDBInitialise(){
             $client = new MongoDB\Client;
             $tweets_database = $client->tweets_db;
             return $tweets_database;
    }
    /**
     * Save single tweet info to mongo database
     *@param $tweet (object) represent tweet object from response
     *@param $tweets_collections (object) represent the tweers collection
     *@return 
     * 
     */
    private function SaveTweetsDataToMongoDB($tweet,$tweets_collections){

         $tweets_info = [
            'id'             => $tweet->id,
            'user_id'        => $tweet->user->id,
            'text'           => $tweet->text,
            'datetime'       => $tweet->created_at,
            'retweets_count' =>$tweet->retweet_count
                ];
            $tweets_collections->insertOne($tweets_info);
    }

      /**
     * Save single tweet user info to mongo database
     *@param $tweet (object) represent tweet object from response
     *@param $tweets_collections (object) represent the users collection
     *@return 
     * 
     */
    private function SaveUsersDataToMongoDb($tweet, $user_collections){
          $users_info = [
                'user_id'         =>$tweet->user->id,
                'name'            => $tweet->user->name,
                'url'             => $tweet->user->url , 
                'followers_count' =>$tweet->user->followers_count   
               ];
            $user_collections->insertOne($users_info);
    }
    
	
}






















?>