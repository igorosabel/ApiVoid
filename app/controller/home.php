<?php
class home extends OController {
	/**
	 * Página temporal, sitio cerrado
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 *
	 * @return void
	 */
	function closed(ORequest $req): void {}

	/**
	 * Home pública
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 *
	 * @return void
	 */
	function index(ORequest $req): void {}

	/**
	 * Página de error 404
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 *
	 * @return void
	 */
	function notFound(ORequest $req): void {}
}