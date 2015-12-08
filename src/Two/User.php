<?php 

namespace Origami\Connect\Two;

use Origami\Connect\AbstractUser;
use Origami\Connect\Token;

class User extends AbstractUser {

	/**
	 * The user's access token.
	 *
	 * @var Token
	 */
	public $token;

	/**
	 * Set the token on the user.
	 *
	 * @param Token $token
	 * @return $this
	 */
	public function setToken(Token $token)
	{
		$this->token = $token;

		return $this;
	}

	/**
	 * Get the user's token
	 *
	 * @return Token
	 */
	public function getToken()
	{
		return $this->token;
	}

}