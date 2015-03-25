<?php

use Mockery as m;
use Illuminate\Http\Request;
use Origami\Connect\Two\User;
use Origami\Connect\Two\AbstractProvider;

class OAuthTwoTest extends PHPUnit_Framework_TestCase {

	public function tearDown()
	{
		m::close();
	}


	public function testRedirectGeneratesTheProperSymfonyRedirectResponse()
	{
		$request = Request::create('foo');
		$request->setSession($session = m::mock('Symfony\Component\HttpFoundation\Session\SessionInterface'));
		$session->shouldReceive('get')->once()->with('_token')->andReturn('token');
		$session->shouldReceive('set')->once();
		$provider = new OAuthTwoTestProviderStub($request, 'client_id', 'client_secret', 'redirect');
		$response = $provider->redirect();

		$this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $response);
		$this->assertEquals('http://auth.url', $response->getTargetUrl());
	}


	public function testUserReturnsAUserInstanceForTheAuthenticatedRequest()
	{
		$request = Request::create('foo', 'GET', ['state' => 'state', 'code' => 'code']);
		$request->setSession($session = m::mock('Symfony\Component\HttpFoundation\Session\SessionInterface'));
		$session->shouldReceive('get')->once()->with('state')->andReturn('state');
		$provider = new OAuthTwoTestProviderStub($request, 'client_id', 'client_secret', 'redirect_uri');
		$provider->http = m::mock('StdClass');
		$provider->http->shouldReceive('post')->once()->with('http://token.url', [
			'headers' => ['Accept' => 'application/json'], 'body' => ['client_id' => 'client_id', 'client_secret' => 'client_secret', 'code' => 'code', 'redirect_uri' => 'redirect_uri'],
		])->andReturn($response = m::mock('StdClass'));
		$response->shouldReceive('getBody')->once()->andReturn('access_token=access_token');
		$user = $provider->user();

		$this->assertInstanceOf('Origami\Connect\Two\User', $user);
		$this->assertEquals('foo', $user->id);
	}


	/**
	 * @expectedException Origami\Connect\Two\InvalidStateException
	 */
	public function testExceptionIsThrownIfStateIsInvalid()
	{
		$request = Request::create('foo', 'GET', ['state' => 'state', 'code' => 'code']);
		$request->setSession($session = m::mock('Symfony\Component\HttpFoundation\Session\SessionInterface'));
		$session->shouldReceive('get')->once()->with('state')->andReturn('state-foo');
		$provider = new OAuthTwoTestProviderStub($request, 'client_id', 'client_secret', 'redirect');
		$user = $provider->user();
	}

}

class OAuthTwoTestProviderStub extends AbstractProvider {

	public $http;

	protected function getAuthUrl($state)
	{
		return 'http://auth.url';
	}

	protected function getTokenUrl()
	{
		return 'http://token.url';
	}

	protected function getUserByToken($token)
	{
		return ['id' => 'foo'];
	}

	protected function mapUserToObject(array $user)
	{
		return (new User)->map(['id' => $user['id']]);
	}

	/**
	 * Get a fresh instance of the Guzzle HTTP client.
	 *
	 * @return \GuzzleHttp\Client
	 */
	protected function getHttpClient()
	{
		if ($this->http) return $this->http;

		return $this->http = m::mock('StdClass');
	}

}
