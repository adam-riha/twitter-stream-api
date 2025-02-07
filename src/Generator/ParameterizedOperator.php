<?php

namespace RWC\TwitterStream\Generator;

use RWC\TwitterStream\Support\Str;

/**
 * @codeCoverageIgnore (internal tool)
 *
 * @internal
 */
class ParameterizedOperator
{
    public string $snakeCasedName;
    public string $nameAsMethodName;

    public function __construct(
        public string $name,
    ) {
        $this->nameAsMethodName = ucfirst($this->name);
        $this->snakeCasedName   = Str::snake($this->name);
    }

    public function methods(): array
    {
        return [
            $this->name,
            "not{$this->nameAsMethodName}",
            "except{$this->nameAsMethodName}",
            "or{$this->nameAsMethodName}",
            "orNot{$this->nameAsMethodName}",
            "orExcept{$this->nameAsMethodName}",
            "and{$this->nameAsMethodName}",
            "andExcept{$this->nameAsMethodName}",
        ];
    }

    public function tests(): array
    {
        $buffer  = [];
        $singles = [['a'], [['b']]];
        $doubles = [['a', 'b'], [['a', 'b']]];

        foreach ($singles as $value) {
            $compiled = is_array($value[0]) ? $value[0][0] : $value[0];
            $buffer   = [
                ...$buffer,
                [$this->name, $value, "{$this->snakeCasedName}:{$compiled}"],
                ["not{$this->nameAsMethodName}", $value, "-{$this->snakeCasedName}:{$compiled}"],
                ["or{$this->nameAsMethodName}", $value, "or {$this->snakeCasedName}:{$compiled}"],
                ["orNot{$this->nameAsMethodName}", $value, "or -{$this->snakeCasedName}:{$compiled}"],
                ["and{$this->nameAsMethodName}", $value, "and {$this->snakeCasedName}:{$compiled}"],
                ["andNot{$this->nameAsMethodName}", $value, "and -{$this->snakeCasedName}:{$compiled}"],
            ];
        }

        foreach ($doubles as $value) {
            $first  = is_array($value[0]) ? $value[0][0] : $value[0];
            /**@phpstan-ignore-next-line */
            $second = is_array($value[0]) ? $value[0][1] : $value[1];

            $buffer = [
                ...$buffer,
                [$this->name, $value, "{$this->snakeCasedName}:{$first} {$this->snakeCasedName}:{$second}"],
                ["not{$this->nameAsMethodName}", $value, "-{$this->snakeCasedName}:{$first} -{$this->snakeCasedName}:{$second}"],
                ["or{$this->nameAsMethodName}", $value, "{$this->snakeCasedName}:{$first} or {$this->snakeCasedName}:{$second}"],
                ["orNot{$this->nameAsMethodName}", $value, "-{$this->snakeCasedName}:{$first} or -{$this->snakeCasedName}:{$second}"],
                ["and{$this->nameAsMethodName}", $value, "{$this->snakeCasedName}:{$first} and {$this->snakeCasedName}:{$second}"],
                ["andNot{$this->nameAsMethodName}", $value, "-{$this->snakeCasedName}:{$first} and -{$this->snakeCasedName}:{$second}"],
            ];
        }

        return $buffer;
    }
}
