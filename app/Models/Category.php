<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image', 'slug'];

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function ads()
    {
        return $this->hasMany(Advertisement::class);
    }

    // Custom scopes to fetch specific categories
    public static function categoryCar()
    {
        return static::where('name', 'car')->first();
    }

    public static function categoryElectronic()
    {
        return static::where('name', 'electronic')->first();
    }
}
