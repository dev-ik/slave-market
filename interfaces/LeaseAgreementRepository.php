<?php

namespace slaveMarket\interfaces;

use slaveMarket\models\LeaseAgreement;

interface LeaseAgreementRepository {

	/**
	 * @param int $slaveId
	 * @param string $timeFrom in format Y-m-d H:i:s
	 * @param string $timeTo in format Y-m-d H:i:s
	 *
	 * @return null|LeaseAgreement
	 */
	public function getForSlave( int $slaveId, string $timeFrom, string $timeTo ): ?LeaseAgreement;

}