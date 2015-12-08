<?php

namespace Origami\Connect;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;

class Token extends Model {

    protected $table = 'auth_tokens';
    protected $fillable = ['user_connect_id','token','refresh_token','source','expires_at'];
    protected $dates = ['expires_at'];

    /* Relations */

    public function auth()
    {
        return $this->belongsTo(Auth::class);
    }

    /* Helpers */

    public function getToken()
    {
        return $this->token;
    }

    public function getRefreshToken()
    {
        return $this->refresh_token;
    }

    public function hasExpired()
    {
        if ( is_null($this->expires_at) ) {
            return false;
        }

        return Carbon::now()->gte($this->expires_at);
    }

    /**
     * @param array $attributes
     * @return static
     * @throws Exception
     */
    public static function make(array $attributes)
    {
        if ( ! isset($attributes['access_token']) ) {
            throw new Exception('Access token unreadable');
        }

        $expires = array_get($attributes, 'expires_in');

        if ( $expires ) {
            $expires = Carbon::now()->addSeconds($expires);
        }

        $token = new static([
            'token' => array_get($attributes, 'access_token'),
            'refresh_token' => array_get($attributes, 'refresh_token'),
            'expires_at' => $expires
        ]);

        $token->exists = false;

        return $token;
    }

}