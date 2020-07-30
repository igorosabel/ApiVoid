<?php declare(strict_types=1);
class home extends OModule {
	/**
	 * Página temporal, sitio cerrado
	 *
	 * @url /closed
	 * @layout blank
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function closed(ORequest $req): void {}

	/**
	 * Home pública
	 *
	 * @url /
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function index(ORequest $req): void {}

	/**
	 * Página de error 404
	 *
	 * @url /not-found
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function notFound(ORequest $req): void {}
}