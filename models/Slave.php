<?php

namespace slaveMarket\models;

use slaveMarket\classes\BaseObject;

/**
 * Class Slave
 * @package slaveMarket\models
 */
class Slave extends BaseObject {

	/**
	 * @var int
	 */
	protected $id;

	/**
	 * @Description Name of Slave
	 * @var string
	 */
	protected $name;

	/**
	 * @Description Price per hour worked
	 * @var float
	 */
	protected $pricePerHour;

	/**
	 * @param int $id
	 * @param string $name
	 * @param float $pricePerHour
	 */
	public function __construct( int $id, string $name, float $pricePerHour ) {
		parent::__construct();
		$this->id           = $id;
		$this->name         = $name;
		$this->pricePerHour = $pricePerHour;
	}

	/**
	 * @return int
	 */
	public function getMaxWorkTime(): int {
		return $this->maxWorkTime;
	}

	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @return float
	 */
	public function getPricePerHour(): float {
		return $this->pricePerHour;
	}

}