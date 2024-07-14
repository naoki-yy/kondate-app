<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'genre_id', 'icon', 'reference_url', 'description'
    ];

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
}
