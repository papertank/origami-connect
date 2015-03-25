<?php namespace Origami\Connect;

use Illuminate\Support\ServiceProvider;

class ConnectServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bindShared('Origami\Connect\Contracts\Factory', function($app)
		{
			return new ConnectManager($app);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['Origami\Connect\Contracts\Factory'];
	}

}
