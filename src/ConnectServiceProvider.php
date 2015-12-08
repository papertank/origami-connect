<?php 

namespace Origami\Connect;

use Illuminate\Support\ServiceProvider;
use Origami\Connect\Console\ConnectTablesCommand;

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

		$this->app->singleton('command.connect.tables', function($app)
		{
			return new ConnectTablesCommand($app['files'], $app['composer']);
		});
		$this->commands('command.connect.tables');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['command.connect.tables', 'Origami\Connect\Contracts\Factory'];
	}

}
