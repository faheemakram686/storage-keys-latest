<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A storage unit price the owner provided. {{plan:slug}} shows its price; no price means "Ask for a quote".
 */
class PricingPlan extends Model
{
    protected $fillable = ['slug', 'name', 'unit_size', 'price', 'currency', 'period', 'features', 'position', 'is_published', 'is_deleted'];

    /** Same defaults as the table, so a new plan has them before it is reloaded. */
    protected $attributes = ['currency' => 'AED', 'period' => 'month', 'position' => 0, 'is_published' => false, 'is_deleted' => false];

    protected $casts = [
        'features' => 'array',
        'is_published' => 'boolean',
    ];

    public function priceText(): string
    {
        if ($this->price === null || $this->price === '') {
            return 'Ask for a quote';
        }
        $amount = (float) $this->price;
        $formatted = number_format($amount, floor($amount) == $amount ? 0 : 2);

        return $this->currency . ' ' . $formatted . ' / ' . $this->period;
    }
}
