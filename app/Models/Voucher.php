<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_amount',
        'start_date',
        'max_redeem',
        'redeem_count',
        'end_date',
        'is_active',
    ];

    public function isValid(): bool
    {
        return $this->isActive() && $this->isInDateRange() && $this->canBeRedeemed();
    }

    private function isActive(): bool
    {
        return $this->is_active;
    }

    private function isInDateRange(): bool
    {
        return $this->end_date >= now();
    }

    private function canBeRedeemed(): bool
    {
        return $this->redeem_count < $this->max_redeem;
    }
}
