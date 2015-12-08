<?php 

namespace Origami\Connect\Contracts;

interface Factory {

	/**
	 * Get an OAuth provider implementation.
	 *
	 * @param  string  $driver
	 * @return \Origami\Connect\Contracts\Provider
	 */
	public function driver($driver = null);

}
