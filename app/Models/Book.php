<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'description', 'author', 'publisher', 'cover', 'price',
        'views', 'stock', 'status', 'created_by', 'updated_by', 'deleted_by',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
