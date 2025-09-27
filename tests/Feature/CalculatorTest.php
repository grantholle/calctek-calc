<?php

use App\Models\Calculation;

it('can view the calculator page', function () {
    $this->get(route('calculator'))
        ->assertOk()
        ->assertInertia(fn (\Inertia\Testing\AssertableInertia $page) => $page
            ->component('Calculator')
            ->has('lastAnswer')
            ->where('currentValue', null)
        );
});

it('can view the calculator page with a specific calculation', function () {
    /** @var Calculation $calculation */
    $calculation = Calculation::factory()->create();

    $this->get(route('calculator', ['calculation' => $calculation->id]))
        ->assertOk()
        ->assertInertia(fn (\Inertia\Testing\AssertableInertia $page) => $page
            ->component('Calculator')
            ->has('lastAnswer')
            ->where('currentValue', fn ($actual) => (float) $actual === $calculation->answer)
        );
});

it('can store a calculation', function () {
    $this->post(route('calculations.store'), [
        'expression' => '2 + 2',
    ])->assertRedirect();

    $this->assertDatabaseHas('calculations', [
        'expression' => '2 + 2',
        'answer' => 4,
    ]);
});

it('can validate invalid expressions', function (string $expression) {
    $this->post(route('calculations.store'), [
        'expression' => $expression,
    ])->assertSessionHasErrors('expression');

    $this->assertDatabaseCount('calculations', 0);
})->with([
    '2 +',
    'abc',
    '5 / 0',
    '',
    '2 ** 3',
    '(2 + 3',
    '2 * 3)',
]);

it('can delete a calculation', function () {
    $calculation = Calculation::factory()->create();

    $this->delete(route('calculations.destroy', $calculation))
        ->assertRedirect()
        ->assertSessionHas('success', 'Calculation deleted successfully.');

    $this->assertModelMissing($calculation);
});

it('can delete all calculations', function () {
    Calculation::factory()->count(5)->create();

    $this->assertDatabaseCount('calculations', 5);

    $this->delete(route('calculations.delete-all'))
        ->assertRedirect(route('calculator'))
        ->assertSessionHas('success', 'Calculation history cleared.');

    $this->assertDatabaseCount('calculations', 0);
});

it('can list historical calculations', function () {
    Calculation::factory(25)->create();

    $this->get(route('calculations.index'))
        ->assertOk()
        ->assertInertia(fn (\Inertia\Testing\AssertableInertia $page) => $page
            ->component('Calculations')
            ->has('calculations.data', 15)
        );
});
