<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $casts = [
        'expires_at' => 'timestamp',
    ];
    protected $table = 'tokens';
    protected $fillable = ['access_token', 'refresh_token', 'users_id'];

    //
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
