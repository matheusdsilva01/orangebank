<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class GoalController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        if (! $user) {
            abort(401);
        }

        $goalProgress = $user->goalProgress()->with('goal')->get();

        return view('goals.index', [
            'goalProgress' => $goalProgress,
        ]);
    }
}
