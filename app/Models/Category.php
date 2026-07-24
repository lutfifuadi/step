<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'icon', 'color', 'description', 'is_active', 'sort_order'];

    public function expressions()
    {
        return $this->hasMany(Expression::class);
    }
}
