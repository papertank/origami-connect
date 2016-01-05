<?php 

namespace Origami\Connect;

use Illuminate\Database\Eloquent\Model;

class Auth extends Model {

    protected $table = 'auth';
    protected $fillable = ['user_id','identifier','name','options'];
    protected $casts = ['options' => 'array'];

    /* Relations */

    public function tokens()
    {
        return $this->hasMany(Token::class, 'auth_id');
    }

    public function user()
    {
    	return $this->belongsTo(config('auth.providers.users.model', 'App\\User'));
    }

    /* Scope */

    public function scopeForType($query, $type)
    {
        return $query->where('name','=',$type);
    }

    /* Helpers */

    public function getLatestToken()
    {
        return $this->tokens()->orderBy('created_at', 'desc')->first();
    }

}