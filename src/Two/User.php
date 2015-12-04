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
	 * The user's refresh token
	 *
	 * @var string
	 */
	public $refresh;

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

	/**
	 * Set the refresh token on the user.
	 *
	 * @param  string  $token
	 * @return $this
	 */
	public function setRefreshToken($refresh)
	{
		$this->refresh = $refresh;

		return $this;
	}

	/**
	 * Get the refresh token for the user.
	 *
	 * @return string
	 */
	public function getRefreshToken()
	{
		return $this->refresh;
	}

}