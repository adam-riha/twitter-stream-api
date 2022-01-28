<?php

use RWC\TwitterStream\Rule;

it('can build a rule', function () {
    $builder = Rule::create('#php')
        ->retweets()
        ->from('@afelixdorn');

    expect((string)$builder)->toBe('#php is:retweet from:@afelixdorn');
});

it('can build a rule with negated conditions', function () {
    $builder = Rule::create('#php')
        ->not->retweets();

    expect((string)$builder)->toBe('#php -is:retweet');
});

it('can build a rule with bounding boxes limitation', function () {
    $builder = Rule::create('#php')
        ->boundingBox([15, 20, 30, 40]);

    expect((string)$builder)->toBe('#php bounding_box:[15 20 30 40]');

    $builder = Rule::create('#php')
        ->boundingBox([
            [15, 20, 30, 40],
            [12, 30, 45, 52]
        ]);

    expect((string)$builder)->toBe('#php bounding_box:[15 20 30 40] bounding_box:[12 30 45 52]');

    $builder = Rule::create('#php')
        ->not->boundingBox([
            [15, 20, 30, 40],
            [12, 30, 45, 52]
        ]);

    expect((string)$builder)->toBe('#php -bounding_box:[15 20 30 40] -bounding_box:[12 30 45 52]');
});

it('can create a rule wih boolean operators', function () {
    $builder = Rule::create('')
        ->raw('apple')
        ->or()
        ->raw('iphone ipad');

    expect((string)$builder)->toBe('apple OR iphone ipad');
});

it('can create a a rule with grouping', function () {
    $builder = Rule::create('skiing')
        ->group(function (Rule $builder) {
            return $builder->raw('-snow')->or()->raw('day')->or()->raw('noschool');
        });

    expect((string)$builder)->toBe('skiing (-snow OR day OR noschool)');
});

it('can not negate the sample field', function () {
    Rule::create()->not->sample(15);
})->throws(LogicException::class);

it('can not not negate the nullcast field', function () {
    Rule::create()->nullcast();
})->throws(LogicException::class);

it('can not negate a group', function () {
    Rule::create()->not->group(function () {
    });
})->throws(LogicException::class);