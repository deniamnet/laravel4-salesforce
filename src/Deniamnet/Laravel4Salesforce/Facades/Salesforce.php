<?php

namespace Deniamnet\Laravel4Salesforce\Facades;

use Illuminate\Support\Facades\Facade;

class Salesforce extends Facade {
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() {
		return 'salesforce';
	}
}
