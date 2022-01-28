<?php

namespace RWC\TwitterStream;

use RWC\TwitterStream\Concerns\CanStream;

class VolumeStream
{
    use CanStream;

    public array $expansions = [];
    public array $fields     = [];
    public int $backfill     = 0;

    public static function new(): self
    {
        return new self();
    }

    public function fields(string $name, string ...$fields): self
    {
        $this->fields[str_replace('.fields', '', $name)] = $fields;

        return $this;
    }

    public function expansions(string ...$expansions): self
    {
        $this->expansions = $expansions;

        return $this;
    }

    public function backfill(int $minutes): self
    {
        $this->backfill = $minutes;

        return $this;
    }

    public function toURL(): string
    {
        $parameters = [];

        foreach ($this->fields as $name => $expansions) {
            $parameters[$name . '.fields'] = implode(',', $expansions);
        }

        if (count($this->expansions) > 0) {
            $parameters['expansions'] = implode(',', $this->expansions);
        }

        if ($this->backfill > 0) {
            $parameters['backfill_minutes'] = $this->backfill;
        }

        return sprintf('https://api.twitter.com/2/tweets/sample/stream?%s', http_build_query($parameters));
    }
}
