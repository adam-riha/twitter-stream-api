<?php

namespace RWC\TwitterStream;

class Attribute {
    public array $values = [];

    public function __construct(
        public string $name,
        array|string|Rule $values,
        public bool $negated = false,
        public bool $headless = false
    )
    {   
        if (!is_array($values)) {
            $this->values = [$values];
        } else {
            $this->values = $values;
        }
    }
}