<?php

use App\Models\Calculation;

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
    '2 * 3)'
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
        ->assertRedirect()
        ->assertSessionHas('success', 'Calculation history cleared.');

    $this->assertDatabaseCount('calculations', 0);
});
