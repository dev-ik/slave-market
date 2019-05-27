<?php

namespace slaveMarket\models;

use slaveMarket\classes\BaseObject;

/**
 * Class Master
 * @package slaveMarket\models
 */
class Master extends BaseObject {

	/**
	 * @var int
	 */
	protected $id;

	/**
	 * @Description Name of Master
	 * @var string
	 */
	protected $name;

	/**
	 * @Description The presence of VIP status
	 * @var boolean
	 */
	protected $vip;

	/**
	 * @param int $id
	 * @param string $name
	 * @param bool $vip
	 */
	public function __construct( int $id, string $name, bool $vip ) {
		parent::__construct();
		$this->id   = $id;
		$this->name = $name;
		$this->vip  = $vip;
	}

	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}

	/**
	 * @Description Returning the presence of VIP status
	 * @return bool
	 */
	public function isVip(): bool {
		return $this->vip;
	}

}