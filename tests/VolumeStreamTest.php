<?php

use RWC\TwitterStream\VolumeStream;

it('can add expansions', function () {
    $url = VolumeStream::new()
        ->expansions('author_id', 'attachments.poll_ids')
        ->toURL();

    expect($url)->toBe('https://api.twitter.com/2/tweets/sample/stream?expansions=author_id%2Cattachments.poll_ids');
});

it('can add fields', function () {
    $url = VolumeStream::new()
        ->fields('media', 'duration_ms', 'height')
        ->fields('tweet', 'author_id')
        ->toURL();

    expect($url)->toBe('https://api.twitter.com/2/tweets/sample/stream?media.fields=duration_ms%2Cheight&tweet.fields=author_id');
});

it('trims fields names', function () {
    $url = VolumeStream::new()
        ->fields('media.fields', 'duration_ms', 'height')
        ->fields('tweet.fields', 'author_id')
        ->toURL();

    expect($url)->toBe('https://api.twitter.com/2/tweets/sample/stream?media.fields=duration_ms%2Cheight&tweet.fields=author_id');
});


it('can set a backfill in minutes', function () {
    $url = VolumeStream::new()
        ->backfill(4)
        ->toURL();

    expect($url)->toBe('https://api.twitter.com/2/tweets/sample/stream?backfill_minutes=4');
});

it('can listen to a stream ');