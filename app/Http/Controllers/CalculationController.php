<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCalculationRequest;
use App\Http\Resources\CalculationResource;
use App\Models\Calculation;
use App\Services\CalculatorService;
use Inertia\Inertia;

class CalculationController extends Controller
{
    public function index()
    {
        return inertia('Calculations', [
            'calculations' => Inertia::scroll(fn () => CalculationResource::collection(
                Calculation::query()
                    ->latest()
                    ->paginate(15)
            )),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCalculationRequest $request)
    {
        $calculator = new CalculatorService($request->expression);

        try {
            $result = $calculator->evaluate();
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['expression' => $e->getMessage()]);
        }

        Calculation::create([
            'expression' => $request->input('expression'),
            'answer' => $result,
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Calculation $calculation)
    {
        $calculation->delete();

        session()->flash('success', 'Calculation deleted successfully.');

        return back();
    }
}
