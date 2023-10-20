<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function attendees(){
        return $this->belongsToMany(User::class,"attendee","user_id","meeting_id");
    }
}
