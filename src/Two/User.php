<?php namespace Origami\Connect\Two;

use Origami\Connect\ConnectUser;

class User extends ConnectUser {

	/**
	 * The user's access token.
	 *
	 * @var string
	 */
	public $token;

	/**
	 * Set the token on the user.
	 *
	 * @param  string  $token
	 * @return $this
	 */
	public function setToken($token)
	{
		$this->token = $token;

		return $this;
	}

}