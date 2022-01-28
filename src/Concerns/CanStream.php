<?php

namespace RWC\TwitterStream\Concerns;

use Closure;
use JsonCollectionParser\Parser;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use RWC\TwitterStream\Connection;

trait CanStream
{
    public ?ResponseInterface $response = null;

    public ?StreamInterface $stream = null;

    public int $received = 0;

    public function listen(Connection $connection, callable $callback): void
    {
        $this->response = $connection->request('GET', $this->toURL(), [
            'stream' => true,
        ]);
        $this->stream = $this->response->getBody();

        $parser = new Parser();
        $parser->parseAsObjects(
            $this->stream,
            function () use ($callback) {
                $this->received++;

                Closure::fromCallable($callback)->call($this, ...func_get_args());
            }
        );
    }

    public function stopListening(): self
    {
        if ($this->stream !== null) {
            @$this->stream->close();
        }

        return $this;
    }

    public function __destruct()
    {
        $this->stopListening();
    }
}
