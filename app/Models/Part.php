<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'article'];

    public function suppliers() {
        return $this->belongsToMany(Supplier::class, 'supplier_parts')
                    ->withPivot('price')
                    ->withTimestamps();
    }
}