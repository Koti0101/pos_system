<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'barcode',
        'category_id',
        'price',
        'quantity',
        'sku',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function isInStock($quantity = 1)
    {
        return $this->quantity >= $quantity;
    }

    public function reduceStock($quantity)
    {
        if ($this->isInStock($quantity)) {
            $this->quantity -= $quantity;
            $this->save();
            return true;
        }
        return false;
    }
}