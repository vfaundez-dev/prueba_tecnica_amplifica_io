<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model {
    
    protected $fillable = [
        'user_id',
        'platform',
        'name',
        'url',
        'api_key',
        'api_secret',
        'access_token',
    ];

    protected $casts = [
        'api_key' => 'encrypted',
        'api_secret' => 'encrypted',
        'access_token' => 'encrypted'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    
}
