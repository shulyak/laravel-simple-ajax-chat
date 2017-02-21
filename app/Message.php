<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Message extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'message'];


    /**
     * Get the user record associated with the message.
     */
    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    /**
     * Get messages
     * @param int $limit
     * @return mixed
     */
    public static function getMessages($limit = 5)
    {
        return self::orderBy('created_at', 'desc')
            ->paginate($limit);
    }

    /**
     * Get messages
     * @param int $userId
     * @return mixed
     */
    public static function getHourMessages($userId)
    {
        $date = new Carbon();
        $lastHour = $date->modify('- 1 hour');

        return self::where('user_id', '<>', $userId)
            ->where('created_at', '>', $lastHour->format('Y-m-d H:i:s'))
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
