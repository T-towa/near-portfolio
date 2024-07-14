<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;
    protected $table = "medias";
    protected $fillable = [
        'post_id', 
        'media_type',
        'media_url'
    ];
}
