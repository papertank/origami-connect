<?php 

namespace Origami\Connect\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Origami\Connect\ConnectManager
 */
class Connect extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'Origami\Connect\Contracts\Factory'; }

}
