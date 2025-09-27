<?php

namespace App\Http\Controllers;

use App\Http\Resources\CalculationResource;
use App\Models\Calculation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CalculatorController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return inertia('Calculator', [
            'lastAnswer' => fn () => Calculation::query()
                ->latest()
                ->value('answer'),
            'currentValue' => fn () => $request->filled('calculation')
                ? Calculation::find($request->input('calculation'))?->answer
                : null,
        ]);
    }
}
