<?php

namespace App\Http\Controllers;

use App\Models\Calculation;

class DeleteAllCalculationsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        Calculation::whereNotNull('id')->delete();

        session()->flash('success', 'Calculation history cleared.');

        return back();
    }
}
