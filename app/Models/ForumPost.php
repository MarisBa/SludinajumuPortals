<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumPost extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * We include user_id and category_id.
     */
    protected $fillable = [
        'user_id',
        'category_id', // <-- New field
        'title',
        'body',
        'feature_image',
        'published',
    ];

    /**
     * A Forum Post belongs to a single User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * A Forum Post belongs to a single Category.
     * This relies on the category_id field.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
