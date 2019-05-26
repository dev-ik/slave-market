<?php

namespace slaveMarket\classes;

/**
 * Class LeaseRequest
 * @package slaveMarket\classes
 */
class LeaseRequest extends BaseObject {

	/**
	 * @Description Unique identification number Master
	 * @var int
	 */
	public $masterId;

	/**
	 * @Description Unique identification number Slave
	 * @var int
	 */
	public $slaveId;

	/**
	 * @Description Start lease time in the format Y-m-d H:i:s
	 * @var string
	 */
	public $startLeaseTime;

	/**
	 * @Description Stop lease time in the format Y-m-d H:i:s
	 * @var string
	 */
	public $stopLeaseTime;

	/**
	 * @param int $masterId
	 * @param int $slaveId
	 * @param string $startLeaseTime in format Y-m-d H:i:s
	 * @param string $stopLeaseTime in format Y-m-d H:i:s
	 */
	public function __construct( int $masterId, int $slaveId, string $startLeaseTime, string $stopLeaseTime ) {
		parent::__construct();
		$this->masterId       = $masterId;
		$this->slaveId        = $slaveId;
		$this->startLeaseTime = $startLeaseTime;
		$this->stopLeaseTime  = $stopLeaseTime;
	}

}