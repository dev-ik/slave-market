<?php


namespace slaveMarket\classes;

use Dotenv\Dotenv;

/**
 * Class Model
 * @package slaveMarket\models
 */
abstract class BaseObject {

	public function __construct() {
		if ( file_exists( __DIR__ . '/../.env' ) ) {
			$dotEnv = Dotenv::create( __DIR__ . '/../' );
			$dotEnv->load();
		}
	}

}