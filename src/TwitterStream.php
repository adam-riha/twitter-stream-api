<?php

namespace RWC\TwitterStream;

use JsonCollectionParser\Parser;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

abstract class TwitterStream
{
    protected int $received                = 0;
    protected ?ResponseInterface $response = null;
    protected ?StreamInterface $stream     = null;

    private int $backfill = 0;
    /** @var string[] */
    private array $fields = [];
    /** @var string[] */
    private array $expansions = [];

    public function listen(Connection $connection, callable $callback): void
    {
        $this->response = $connection->request('GET', $this->toURL(), ['stream' => true]);
        $this->stream   = $this->response->getBody();

        $parser = new Parser();
        $parser->parseAsObjects(
            $this->stream,
            function () use ($callback) {
                $this->received++;

                $callback(...)->call($this, ...func_get_args());
            }
        );
    }

    public function toURL(): string
    {
        $parameters = [];

        foreach ($this->fields as $field) {
            [$type, $field] = explode('.', $field);

            if (array_key_exists($type . '.fields', $parameters)) {
                $parameters[$type . '.fields'] .= ',' . $field;
            } else {
                $parameters[$type . '.fields'] = $field;
            }
        }

        if (count($this->expansions) > 0) {
            $parameters['expansions'] = implode(',', $this->expansions);
        }

        if ($this->backfill > 0) {
            $parameters['backfill_minutes'] = $this->backfill;
        }

        return sprintf('%s?%s', $this->endpoint(), http_build_query($parameters));
    }

    abstract public function endpoint(): string;

    public function __destruct()
    {
        $this->stopListening();
    }

    public function stopListening(): self
    {
        $this->stream?->close();

        return $this;
    }

    public function backfill(int $minutes): self
    {
        $this->backfill = $minutes;

        return $this;
    }

    public function expansions(string ...$expansions): self
    {
        $this->expansions = [...$this->expansions, ...$expansions];

        return $this;
    }

    public function fields(string ...$fields): self
    {
        $this->fields = [...$this->fields, ...$fields];

        return $this;
    }
}
