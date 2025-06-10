<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Childcategory;
use App\Models\Country;
use App\Models\State;
use App\Models\User;

class Advertisement extends Model
{
    use HasFactory;
    protected $guarded = [];


        public function childcategory()
    {
        return $this->hasOne(Childcategory::class, 'id', 'childcategory_id');
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