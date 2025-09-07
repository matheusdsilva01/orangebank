<?php

namespace App\View\Components;

use App\Models\Stock;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CardStockPurchased extends Component
{
    public float $variation;

    /**
     * Create a new component instance.
     */
    public function __construct(public Stock $stock)
    {
        $this->variation = round((($this->stock->current_price - $this->stock->pivot->purchase_price) / $this->stock->pivot->purchase_price) * 100, 1);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.card-stock-purchased');
    }
}
