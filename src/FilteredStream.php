<?php

namespace RWC\TwitterStream;

use RWC\TwitterStream\Concerns\CanStream;

class FilteredStream {
    use CanStream;

    /** @var []Rule $rules */
    public array $rules = [];

    public function rules(
        Rule ...$rules
    ): self {
        $this->rules = $rules;
        
        return $this;
    }

    public static function new(): self
    {
        return new self();
    }
}