<?php namespace Origami\Connect;

use Illuminate\Support\Manager;
use Origami\Connect\Two\GithubProvider;
use Origami\Connect\Two\GoogleProvider;
use Origami\Connect\One\TwitterProvider;
use Origami\Connect\Two\FacebookProvider;
use League\OAuth1\Client\Server\Twitter as TwitterServer;
use Origami\Connect\One\AbstractProvider as AbstractOneProvider;
use Origami\Connect\Two\AbstractProvider as AbstractTwoProvider;

class ConnectManager extends Manager implements Contracts\Factory {

	/**
	 * Get a driver instance.
	 *
	 * @param  string  $driver
	 * @return mixed
	 */
	public function with($driver)
	{
		return $this->driver($driver);
	}

	/**
	 * Create an instance of the specified driver.
	 *
	 * @return \Origami\Connect\Two\AbstractProvider
	 */
	protected function createGithubDriver()
	{
		$config = $this->app['config']['services.github'];

		return $this->buildProvider(
			'Origami\Connect\Two\GithubProvider', $config
		);
	}

	/**
	 * Create an instance of the specified driver.
	 *
	 * @return \Origami\Connect\Two\AbstractProvider
	 */
	protected function createFacebookDriver()
	{
		$config = $this->app['config']['services.facebook'];

		return $this->buildProvider(
			'Origami\Connect\Two\FacebookProvider', $config
		);

	}

	/**
	 * Create an instance of the specified driver.
	 *
	 * @return \Origami\Connect\Two\AbstractProvider
	 */
	protected function createGoogleDriver()
	{
		$config = $this->app['config']['services.google'];

		return $this->buildProvider(
			'Origami\Connect\Two\GoogleProvider', $config
		);
	}

	/**
	 * Build an OAuth 2 provider instance.
	 *
	 * @param  string  $provider
	 * @param  array  $config
	 * @return \Origami\Connect\Two\AbstractProvider
	 */
	protected function buildProvider($provider, $config)
	{
		return new $provider(
			$this->app['request'], $config['client_id'],
			$config['client_secret'], $config['redirect']
		);
	}

	/**
	 * Create an instance of the specified driver.
	 *
	 * @return \Origami\Connect\One\AbstractProvider
	 */
	protected function createTwitterDriver()
	{
		$config = $this->app['config']['services.twitter'];

		return new TwitterProvider(
			$this->app['request'], new TwitterServer($this->formatConfig($config))
		);
	}

	/**
	 * Format the Twitter server configuration.
	 *
	 * @param  array  $config
	 * @return array
	 */
	protected function formatConfig(array $config)
	{
		return [
			'identifier' => $config['client_id'],
			'secret' => $config['client_secret'],
			'callback_url' => $config['redirect'],
		];
	}

	/**
	 * Get the default driver name.
	 *
	 * @return string
	 */
	public function getDefaultDriver()
	{
		throw new \InvalidArgumentException("No Connect driver was specified.");
	}

}
