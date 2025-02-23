<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = ['supplier_id', 'purchase_date', 'status'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function parts()
    {
        return $this->belongsToMany(Part::class, 'purchase_items', 'purchase_id', 'supplier_part_id')
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function getTotalAmountAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });
    }
}
