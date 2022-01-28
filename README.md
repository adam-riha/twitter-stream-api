## Usage


```php
$connection = new Connection("...");

VolumeStream::new()
  ->listen($connection, function (array $tweet) {
      echo $tweet['data']['text'];
  });


```