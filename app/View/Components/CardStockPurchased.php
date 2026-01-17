<?php

namespace App\View\Components;

use App\Models\Stock;
use Brick\Math\RoundingMode;
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
        // round((($this->stock->current_price - $this->stock->pivot->purchase_price) / $this->stock->pivot->purchase_price) * 100, 1)
        $this->variation = $this->stock->current_price
            ->minus($this->stock->pivot->purchase_price)
            ->dividedBy($this->stock->pivot->purchase_price->getAmount(), RoundingMode::HALF_EVEN)
            ->getAmount()
            ->toFloat() * 100;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.card-stock-purchased');
    }
}
