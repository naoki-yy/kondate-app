<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'dish_id', 'material', 'amount'
    ];

    public function dish()
    {
        return $this->belongsTo(Dish::class);
    }
}
