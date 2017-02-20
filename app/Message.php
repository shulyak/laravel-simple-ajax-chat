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
        return $this->hasOne('App\User');
    }

    /**
     * Get messages
     * @param int $limit
     * @param array $params
     * @return mixed
     */
    public static function getMessages($limit = 5, $params = [])
    {
        return DB::table('messages')
            ->select('messages.id', 'messages.user_id', 'messages.message', 'messages.created_at', 'users.name AS username')
            ->join('users', 'users.id', '=', 'messages.user_id')
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }

    /**
     * Get messages
     * @param int $limit
     * @param array $params
     * @return mixed
     */
    public static function getHourMessages($userId)
    {
        $date = new Carbon();
        $lastHour = $date->modify('- 1 hour');

        return DB::table('messages')
            ->select('messages.id', 'messages.user_id', 'messages.message', 'messages.created_at', 'users.name AS username')
            ->join('users', 'users.id', '=', 'messages.user_id')
            ->where('messages.user_id', '<>', $userId)
            ->where('messages.created_at', '>', $lastHour->format('Y-m-d H:i:s'))
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
