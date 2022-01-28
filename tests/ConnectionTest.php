<?php

use GuzzleHttp\Exception\ClientException;
use RWC\TwitterStream\Connection;

it('can not connect to twitter with an invalid bearer token', function () {
    $connection = new Connection('token_is_not_valid');

    $connection->request('GET', 'https://api.twitter.com/2/tweets?ids=20');
})->throws(ClientException::class);

it('can connect to twitter', function () {
   $connection = new Connection(
      getenv('TWITTER_BEARER_TOKEN') 
   ); 

   $resource = $connection->request('GET', 'https://api.twitter.com/2/tweets?ids=20')->getBody()->getContents();

   $response = json_decode($resource, associative: true);


   expect($response['data'][0]['id'])->toBe('20');
   expect($response['data'][0]['text'])->toBe('just setting up my twttr');
});