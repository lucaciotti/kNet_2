<?php

namespace knet;

use Illuminate\Database\Eloquent\Model;

class UserAutoReports extends Model
{
    protected $connection = 'kNet';
    protected $table = 'userAutoReports';

    public function user()
    {
        return $this->hasOne('knet\User', 'id', 'user_id');
    }
}
