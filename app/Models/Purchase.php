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


    const STATUS_IN_PROGRESS = 0;
    const STATUS_CANCELED = 1;
    const STATUS_COMPLETED = 2;

    public static function getStatusList()
    {
        return [
            self::STATUS_IN_PROGRESS => 'В процессе',
            self::STATUS_CANCELED => 'Отменена',
            self::STATUS_COMPLETED => 'Завершена',
        ];
    }

    public function getStatusTextAttribute()
    {
        return self::getStatusList()[$this->status] ?? 'Неизвестный статус';
    }

}
