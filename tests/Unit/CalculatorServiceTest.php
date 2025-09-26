<?php

use App\Services\CalculatorService;

test('can calculate expected results from given expression', function (string $expression, float|string $expected) {

    $calculator = new CalculatorService($expression);

    try {
        $result = $calculator->evaluate();
        expect($result)->toBe($expected);
    } catch (\InvalidArgumentException $e) {
        expect($e->getMessage())->toBe($expected);
    }
})->with([
    'simple addition' => ['2 + 5', 7.0],
    'simple subtraction' => ['10 - 3', 7.0],
    'simple multiplication' => ['4 * 2.5', 10.0],
    'simple division' => ['20 / 4', 5.0],
    'mixed operations' => ['2 + 3 * 4', 14.0],
    'it rounds float results down' => ['7 / 3', 2.3333333333],
    'it rounds float results up' => ['10 / 6', 1.6666666667],
    'operations with parentheses' => ['(2 + 3) * 4', 20.0],
    'mixed operations with parentheses' => ['(2 + 3) * (4 - 1)', 15.0],
    'nested parentheses' => ['((2 + 3) * (4 - 1)) / 5', 3.0],
    'fails on empty expression' => ['', 'Empty expression'],
    'fails on invalid characters' => ['2 + a', 'Invalid characters in expression'],
    'fails on unmatched parentheses' => ['(2 + 3', 'Unmatched opening parenthesis at position 1'],
    'fails on unmatched closing parentheses' => ['2 + 3)', 'Unmatched closing parenthesis at position 4'],
]);
