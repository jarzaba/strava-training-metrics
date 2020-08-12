<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activities extends Model
{
    //
    //protected $casts = [
    //  'start_date' => 'timestamp',
    //];
    protected $table = 'activities';
    protected $fillable = ['id', 'activity_id', 'activities_user_id', 'start_date', 'distance', 'elapsed_time', 'moving_time', 'elevation_gain', 'average_speed', 'type', 'polyline'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
