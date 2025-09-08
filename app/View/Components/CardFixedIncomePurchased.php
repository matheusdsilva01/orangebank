<?php

namespace App\View\Components;

use App\Models\FixedIncome;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CardFixedIncomePurchased extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public FixedIncome $fixedIncome
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.card-fixed-income-purchased');
    }
}
