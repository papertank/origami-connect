<?php namespace Origami\Connect\One;

use Origami\Connect\ConnectUser;

class User extends ConnectUser {

	/**
	 * The user's access token.
	 *
	 * @var string
	 */
	public $token;

	/**
	 * The user's access token secret.
	 *
	 * @var string
	 */
	public $tokenSecret;

	/**
	 * Set the token on the user.
	 *
	 * @param  string  $token
	 * @param  string  $tokenSecret
	 * @return $this
	 */
	public function setToken($token, $tokenSecret)
	{
		$this->token = $token;
		$this->tokenSecret = $tokenSecret;

		return $this;
	}

}