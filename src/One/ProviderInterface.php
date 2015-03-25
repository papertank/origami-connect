<?php namespace Origami\Connect\One;

interface ProviderInterface {

	/**
	 * Redirect the user to the authentication page for the provider.
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function redirect();

	/**
	 * Get the User instance for the authenticated user.
	 *
	 * @return \Origami\Connect\One\User
	 */
	public function user();

}