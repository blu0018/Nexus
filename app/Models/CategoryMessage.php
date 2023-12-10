<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryMessage extends Model
{
    use HasFactory;
    public $table = "category_messages";

    public function categorychat(){
        return $this->belongsTo(CategoryChat::class, 'chat_id','chat_id');
    }
}
