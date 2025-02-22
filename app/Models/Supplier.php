<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'phone'];

    public function supplierParts() {
        return $this->hasMany(SupplierPart::class);
    }

    public function purchases() {
        return $this->hasMany(Purchase::class);
    }

    public function parts()
{
    return $this->belongsToMany(Part::class, 'supplier_parts')
                ->withPivot('price')
                ->withTimestamps();
}
}
