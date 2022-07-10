<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable =[
        'body',
        'user_id',
        'reciver_id'
    ];
    public function photos(){
        return $this->morphMany(Photo::class,"photoable");
    }

   public function user(){
    return $this->belongsTo(User::class);
   }

   public function reciver(){
    return $this->belongsTo(User::class,'reciver_id');
   }
}
