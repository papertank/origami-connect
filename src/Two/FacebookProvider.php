<?php namespace Origami\Connect\Two;

use Symfony\Component\HttpFoundation\RedirectResponse;

class FacebookProvider extends AbstractProvider implements ProviderInterface {

	/**
	 * The scopes being requested.
	 *
	 * @var array
	 */
	protected $scopes = ['email'];

	/**
	 * {@inheritdoc}
	 */
	protected function getAuthUrl($state)
	{
		return $this->buildAuthUrlFromBase('https://www.facebook.com/dialog/oauth', $state);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getTokenUrl()
	{
		return 'https://graph.facebook.com/oauth/access_token';
	}

	/**
	 * Get the access token for the given code.
	 *
	 * @param  string  $code
	 * @return string
	 */
	public function getAccessToken($code)
	{
		$response = $this->getHttpClient()->get($this->getTokenUrl(), [
			'query' => $this->getTokenFields($code),
		]);

		return (string) $response->getBody();
	}

	/**
	 * {@inheritdoc}
	 */
	public function parseAccessToken($body)
	{
		parse_str($body);

		return $access_token;
	}

	/**
	 * Get the refresh token from the token response body.
	 *
	 * @param  string  $body
	 * @return string
	 */
	public function parseRefreshToken($body)
	{
		return null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getUserByToken($token)
	{
		$response = $this->getHttpClient()->get('https://graph.facebook.com/me?access_token='.$token, [
			'headers' => [
				'Accept' => 'application/json',
			],
		]);

		return json_decode($response->getBody(), true);
	}

	/**
	 * {@inheritdoc}
	 */
	public function mapUserToObject(array $user)
	{
		return (new User)->setRaw($user)->map([
			'id' => $user['id'], 'nickname' => null, 'name' => $user['first_name'].' '.$user['last_name'],
			'email' => $user['email'], 'avatar' => 'https://graph.facebook.com/'.$user['id'].'/picture?type=normal',
		]);
	}

}