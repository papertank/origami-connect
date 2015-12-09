<?php

namespace Origami\Connect;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Token extends Model {

    protected $table = 'auth_tokens';
    protected $fillable = ['user_connect_id','token','refresh_token','source','expires_at'];
    protected $dates = ['expires_at'];

    protected $driver;

    /* Create */

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

    public function refreshAccessToken()
    {
        try {

            $new = $this->getDriver()->refreshAccessToken($this);

            $this->fill([
                'access_token' => $new->getToken(),
                'refresh_token' => $new->getRefreshToken(),
                'expires_at' => $new->expires_at,
            ]);

            $this->save();

            return $this;

        } catch ( Exception $e ) {
            Log::error($e);
            return $this;
        }
    }

    protected function getDriver()
    {
        if ( is_null($this->driver) ) {
            $this->driver = app('Origami\Connect\Contracts\Factory')->driver($this->auth->name);
        }

        return $this->driver;
    }

}