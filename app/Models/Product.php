<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes, Uuid;

    function parent_category() {
        return $this->hasOne(Category::class, 'id', 'parent_category_id');
    }

    function child_category() {
        return $this->hasOne(Category::class, 'id', 'child_category_id');
    }

    function media() {
        return $this->hasMany(ProductMedia::class);
    }

    function sizes() {
        return $this->hasMany(ProductSize::class);
    }

    function variants() {
        return $this->hasMany(ProductVariant::class);
    }

}
