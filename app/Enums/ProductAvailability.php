<?php 

namespace App\Enums;

enum ProductAvailability:string
{
    case IN_STOCK = 'IN_STOCK';
    case OUT_OF_STOCK = 'OUT_OF_STOCK';
    case PRE_ORDER = 'PRE_ORDER';

    public function label(): string {
        return ucwords(str_replace('_',' ',strtolower($this->name)));
    }
}