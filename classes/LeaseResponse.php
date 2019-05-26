<?php

namespace slaveMarket\classes;

use slaveMarket\models\LeaseAgreement;

/**
 * Class LeaseResponse
 * @package slaveMarket\classes
 */
class LeaseResponse extends BaseObject {

	/**
	 * @Description Lease agreement
	 * @var LeaseAgreement
	 */
	public $leaseAgreement = null;

	/**
	 * @Description Error list
	 * @var string[]
	 */
	public $errors = [];

	/**
	 * @return null|LeaseAgreement
	 */
	public function getLeaseAgreement(): ?LeaseAgreement {
		return $this->leaseAgreement;
	}

	/**
	 * @Description Set lease agreements
	 * @param LeaseAgreement $leaseAgreement
	 */
	public function setLeaseAgreement( LeaseAgreement $leaseAgreement ): void {
		$this->leaseAgreement = $leaseAgreement;
	}

	/**
	 * @Description Adding error message to error list
	 * @param string $message
	 */
	public function addErrors( string $message ): void {
		$this->errors[] = $message;
	}

	/**
	 * @Description Return empty or errors list
	 * @return string[]
	 */
	public function getErrors(): array {
		return $this->errors;
	}
}