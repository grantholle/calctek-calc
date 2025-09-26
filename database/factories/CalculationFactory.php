<?php

namespace Database\Factories;

use App\Services\CalculatorService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Calculation>
 */
class CalculationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $operator = $this->faker->randomElement(['+', '-', '*', '/']);
        $operand1 = $this->faker->numberBetween(1, 100);
        $operand2 = $this->faker->numberBetween(1, 100);

        $expression = $operand1.$operator.$operand2;

        return [
            'expression' => $expression,
            'answer' => new CalculatorService($expression)
                ->evaluate(),
        ];
    }
}
