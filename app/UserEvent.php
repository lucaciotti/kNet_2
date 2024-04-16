<?php

namespace knet;

use Illuminate\Database\Eloquent\Model;

class UserEvent extends Model
{

    protected $fillable = [
        'title', 'start', 'end', 'comments', 'user_id'
    ];

}
