<?php

namespace App\Services;

class CalculatorService
{
    protected int $index = 0;
    protected string $expression;
    protected array $tokens = [];

    public function __construct(string $expression)
    {
        $this->expression = str_replace(' ', '', $expression);
    }

    public function evaluate(): float
    {
        if ($message = $this->validationMessage()) {
            throw new \InvalidArgumentException($message);
        }

        $this->index = 0;

        $result = $this->tokenize()
            ->parseAdditionSubtraction();

        return round($result, 10);
    }

    public function validationMessage(): ?string
    {
        if (empty($this->expression)) {
            return 'Empty expression';
        }

        if (! preg_match('/^[0-9+\-*\/().]+$/', $this->expression)) {
            return 'Invalid characters in expression';
        }

        if ($message = $this->invalidParenthesesMessage()) {
            return $message;
        }

        return null;
    }

    protected function invalidParenthesesMessage(): ?string
    {
        $stack = null;

        for ($i = 0; $i < strlen($this->expression); $i++) {
            $char = $this->expression[$i];

            if ($char === '(') {
                $stack[] = $i + 1;
            } elseif ($char === ')') {
                if (empty($stack)) {
                    return "Unmatched closing parenthesis at position " . ($i + 1);
                }

                array_pop($stack);
            }
        }

        if (! empty($stack)) {
            $pos = array_pop($stack);
            return "Unmatched opening parenthesis at position {$pos}";
        }

        return null;
    }

    private function tokenize(): static
    {
        $this->tokens = [];
        $currentNumber = '';

        for ($i = 0; $i < strlen($this->expression); $i++) {
            $char = $this->expression[$i];

            if (is_numeric($char) || $char === '.') {
                $currentNumber .= $char;
            } else {
                if ($currentNumber !== '') {
                    $this->tokens[] = (float) $currentNumber;
                    $currentNumber = '';
                }
                $this->tokens[] = $char;
            }
        }

        if ($currentNumber !== '') {
            $this->tokens[] = (float) $currentNumber;
        }

        return $this;
    }

    private function parseAdditionSubtraction(): float
    {
        $left = $this->parseMultiplicationDivision();

        while ($this->index < count($this->tokens) && in_array($this->tokens[$this->index], ['+', '-'])) {
            $operator = $this->tokens[$this->index++];
            $right = $this->parseMultiplicationDivision();

            if ($operator === '+') {
                $left += $right;
            } else {
                $left -= $right;
            }
        }

        return $left;
    }

    private function parseMultiplicationDivision(): float
    {
        $left = $this->parseFactor();

        while ($this->index < count($this->tokens) && in_array($this->tokens[$this->index], ['*', '/'])) {
            $operator = $this->tokens[$this->index++];
            $right = $this->parseFactor();

            if ($operator === '*') {
                $left *= $right;
            } else {
                if ($right == 0) {
                    throw new \InvalidArgumentException('Division by zero');
                }

                $left /= $right;
            }
        }

        return $left;
    }

    private function parseFactor(): float
    {
        if ($this->index >= count($this->tokens)) {
            throw new \InvalidArgumentException('Unexpected end of expression');
        }

        $token = $this->tokens[$this->index++];

        if (is_numeric($token)) {
            return $token;
        }

        if ($token === '(') {
            $result = $this->parseAdditionSubtraction();

            if ($this->index >= count($this->tokens) || $this->tokens[$this->index] !== ')') {
                throw new \InvalidArgumentException('Missing closing parenthesis');
            }

            // Skip the closing parenthesis
            $this->index++;
            return $result;
        }

        // Handle unary minus
        if ($token === '-') {
            return -$this->parseFactor();
        }

        throw new \InvalidArgumentException('Unexpected token: ' . $token);
    }
}
