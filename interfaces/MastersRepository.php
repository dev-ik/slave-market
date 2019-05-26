<?php

namespace slaveMarket\interfaces;

use slaveMarket\models\Master;

/**
 * Interface MastersRepository
 * @package slaveMarket\interfaces
 */
interface MastersRepository {

	/**
	 * @Description Return Master by Id
	 *
	 * @param int $id
	 *
	 * @return Master
	 */
	public function findById( int $id ): Master;

}