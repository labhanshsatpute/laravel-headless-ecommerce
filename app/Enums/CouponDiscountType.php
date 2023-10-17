<?php 

namespace App\Enums;

enum CouponDiscountType: string
{
    case FIXED = 'FIXED';
    case PERCENTAGE = 'PERCENTAGE';

    public function label(): string {
        return ucwords(str_replace('_',' ',strtolower($this->name)));
    }
}