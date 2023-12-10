<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryChat extends Model
{
    use HasFactory;
    public $table = "category_chats";


    public function chatmessage(){
        return $this->hasMany(CategoryMessage::class,'chat_id','chat_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

}
