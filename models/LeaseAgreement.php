<?php

namespace slaveMarket\models;

use slaveMarket\classes\BaseObject;

/**
 * Class LeaseAgreement
 * @package slaveMarket\models
 */
class LeaseAgreement extends BaseObject {

	/**
	 * @Description Unique key for identity Master
	 * @var int
	 */
	protected $masterId;

	/**
	 * @Description Unique key for identity Slave
	 * @var int
	 */
	protected $slaveId;

	/**
	 * @Description Cost of the lease of the slave
	 * @var float
	 */
	protected $price;

	/**
	 * @Description Start lease time
	 * @var string
	 */
	protected $startLeaseTime;

	/**
	 * @Description Stop lease time
	 * @var string
	 */
	protected $stopLeaseTime;

	/**
	 * @param int $masterId
	 * @param int $slaveId
	 * @param float $price
	 * @param string $startLeaseTime in format Y-m-d H:i:s
	 * @param string $stopLeaseTime n format Y-m-d H:i:s
	 */
	public function __construct( int $masterId, int $slaveId, float $price, string $startLeaseTime, string $stopLeaseTime ) {
		parent::__construct();
		$this->masterId       = $masterId;
		$this->slaveId        = $slaveId;
		$this->price          = $price;
		$this->startLeaseTime = $startLeaseTime;
		$this->stopLeaseTime  = $stopLeaseTime;
	}

	/**
	 * @return int
	 */
	public function getMasterId(): int {
		return $this->masterId;
	}

	/**
	 * @return string
	 */
	public function getStartLeaseTime(): string {
		return $this->startLeaseTime;
	}

	/**
	 * @return string
	 */
	public function getStopLeaseTime(): string {
		return $this->stopLeaseTime;
	}

}