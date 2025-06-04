<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Category;
use App\Models\Childcategory;
class Subcategory extends Model
{
    use HasFactory;
    protected $fillable= ['name','category_id','slug'];


    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class);
    }

    public function childcategories()
   {
       return $this->hasMany(Childcategory::class);
   }
}


