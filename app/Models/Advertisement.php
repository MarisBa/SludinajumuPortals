<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Childcategory;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\User;

class Advertisement extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = ['images' => 'array'];

    /**
     * Get all images (combines legacy columns + new JSON column).
     * Returns array of image paths/URLs.
     */
    public function getAllImages(): array
    {
        $imgs = [];
        if (!empty($this->feature_image)) $imgs[] = $this->feature_image;
        if (!empty($this->first_image)) $imgs[] = $this->first_image;
        if (!empty($this->second_image)) $imgs[] = $this->second_image;
        if (!empty($this->images) && is_array($this->images)) {
            $imgs = array_merge($imgs, $this->images);
        }
        return array_values(array_unique($imgs));
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function childcategory()
    {
        return $this->belongsTo(Childcategory::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function state()
    {
        return $this->belongsTo(State::class);
    }
     public function user()
    {
        return $this->belongsTo(User::class);
    }
}