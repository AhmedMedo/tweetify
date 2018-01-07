# tweetify
Simple PHP script to get tweets from twitter using search api
# Notes
- tmhOAuth.php library only used to connect to twitter search api
- twitter.ini file used to configure the parameters to establish the api connection and make sure that you register your app in twitter 
- Class TwitterSearchApi is responsible for get tweets from twitter search api located in app/library/TwitterSearchApi.php
- TwitterSearchApi()::StartTwitterSearch($word,$type) pass to it the search word and type [popular or recent]
- make sure that phalcon is installed in your server check  [here](https://olddocs.phalconphp.com/en/3.0.0/reference/wamp.html) 
- run this command to start work
```
phalcon serve
```
-type this url to start getting tweets from twitter
```
http://localhost:8000/start
```
- after getting the tweets it will upload top 20 users to firebase
