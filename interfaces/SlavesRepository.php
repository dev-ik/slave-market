<?php

namespace slaveMarket\interfaces;

use slaveMarket\models\Slave;

/**
 * Interface SlavesRepository
 * @package slaveMarket\interfaces
 */
interface SlavesRepository {

	/**
	 * @Description Return Slave by Id
	 *
	 * @param int $id
	 *
	 * @return Slave
	 */
	public function findById( int $id ): Slave;

}