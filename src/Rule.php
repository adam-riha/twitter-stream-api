<?php

namespace RWC\TwitterStream;

use LogicException;
use Psr\Http\Message\ResponseInterface;
use SplStack;

class Rule {
    protected bool $negates = false;
    /** @var SplStack<Attribute> */
    protected SplStack $attributes;

    public function __construct(string $query = '')
    {
        $this->attributes = new SplStack();
        $this->attributes->push(
            new Attribute('QUERY', $query, headless: true)
        );
    }

    public static function create(string $query = ''): self {
        return new self($query);
    }


    public function saveWith(Connection $connection): ResponseInterface {
        return $connection->request('POST', 'TODO');
    }

    public function __get(string $name) {
        if ($name !== "not") {
            trigger_error('Undefined property QueryBuilder::' . $name, E_USER_WARNING);

            return null;
        }

        return $this->negates();
    }

    public function __set(string $name, mixed $value): void {
        if (empty($value)) {
            $this->negates(false);

            return;
        }

        $isHeadless = in_array($name, ['and', 'or', 'query', 'raw']);


        $this->attributes->push(new Attribute(
            $isHeadless ? strtoupper($name) : $name,
            $value,
            $this->negates,
            $isHeadless            
        ));
    }

    public function __call(string $name, array $arguments = []) {
        $this->{$name} = $arguments[0]
    }

    protected function negates(bool $negates = true): self {
        $this->negates = $negates;

        return $this;
    }

    public function group(callable $builder): self {
        if ($this->negates) {
            throw new LogicException('A group can not be negated. Negate each individual statement.');
        }

        $stub = new self();
        $builder($stub);
        // This calls __set
        $this->group = $stub;

        return $this;
    }
}