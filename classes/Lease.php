<?php

namespace slaveMarket\classes;

use slaveMarket\helpers\LeaseHelper;
use slaveMarket\interfaces\LeaseAgreementRepository;
use slaveMarket\interfaces\MastersRepository;
use slaveMarket\interfaces\SlavesRepository;
use slaveMarket\models\LeaseAgreement;

/**
 * Class Lease
 * @package slaveMarket\classes
 */
class Lease extends BaseObject {

	/**
	 * @var MastersRepository
	 */
	public $mastersRepository;

	/**
	 * @var SlavesRepository
	 */
	public $slavesRepository;

	/**
	 * @var LeaseAgreementRepository
	 */
	public $leaseAgreementRepository;

	/**
	 * @var LeaseResponse
	 */
	protected $response;

	/**
	 * @var LeaseHelper
	 */
	protected $leaseHelper;

	/**
	 * @param MastersRepository $mastersRepository
	 * @param SlavesRepository $slavesRepository
	 * @param LeaseAgreementRepository $leaseAgreementRepository
	 */
	public function __construct( MastersRepository $mastersRepository, SlavesRepository $slavesRepository, LeaseAgreementRepository $leaseAgreementRepository ) {

		parent::__construct();

		$this->mastersRepository        = $mastersRepository;
		$this->slavesRepository         = $slavesRepository;
		$this->leaseAgreementRepository = $leaseAgreementRepository;
		$this->leaseHelper              = new LeaseHelper();
		$this->response                 = new LeaseResponse();
	}

	/**
	 * @param LeaseRequest $request
	 *
	 * @return LeaseResponse
	 */
	public function run( LeaseRequest $request ): LeaseResponse {

		$master = $this->mastersRepository->findById( $request->masterId );
		$slave  = $this->slavesRepository->findById( $request->slaveId );

		if ( ! $this->validateRequestTimeFormat( $request ) ) {
			return $this->response;
		}

		if ( $error = $this->leaseHelper->checkMaxWorkTimeSlave( $request->startLeaseTime, $request->stopLeaseTime ) ) {
			$errors[] = $error;
		}

		if ( $leaseForSlave = $this->leaseAgreementRepository->getForSlave( $slave->getId(), $request->startLeaseTime,
			$request->stopLeaseTime ) ) {
			$masterHavingLeaseAgreement = $this->mastersRepository->findById( $leaseForSlave->getMasterId() );

			if ( $error = $this->leaseHelper->checkAllowedLeaseTimeForMaster( $master, $masterHavingLeaseAgreement, $leaseForSlave, $request->startLeaseTime, $request->stopLeaseTime ) ) {
				$error = 'Раб ' . $slave->getName() . ' занят. ' . $error;
				$this->response->addErrors( $error );
			}
		}


		if ( empty( $this->response->getErrors() ) ) {
			$leasePrice     = $this->leaseHelper->getLeasePrice( $slave->getPricePerHour(), $request->startLeaseTime, $request->stopLeaseTime );
			$startLeaseHour = date( 'H', strtotime( $request->startLeaseTime ) );
			$startLeaseTime = date( "Y-m-d H", strtotime( $request->startLeaseTime ) );
			$stopLeaseHour  = date( 'H', strtotime( $request->stopLeaseTime ) );
			if ( $startLeaseHour === $stopLeaseHour ) {
				$stopLeaseTime = date( "Y-m-d H", strtotime( $request->stopLeaseTime ) + 3600 );
			} else {
				$stopLeaseTime = date( "Y-m-d H", strtotime( $request->stopLeaseTime ) );
			}
			$leaseAgreement = new LeaseAgreement( $master->getId(), $slave->getId(), $leasePrice, $startLeaseTime, $stopLeaseTime );

			$this->response->setLeaseAgreement( $leaseAgreement );
		}

		return $this->response;
	}


	/**
	 * @param LeaseRequest $request
	 *
	 * @return bool
	 */
	public function validateRequestTimeFormat( LeaseRequest $request ): bool {
		if ( ! $this->leaseHelper->validateTimeFormat( $request->startLeaseTime ) ) {
			$this->response->addErrors( 'Дата начала аренды указана в неверном формате' );
		}

		if ( ! $this->leaseHelper->validateTimeFormat( $request->stopLeaseTime ) ) {
			$this->response->addErrors( 'Дата окончания аренды указана в неверном формате' );
		}

		return empty( $this->response->getErrors() );
	}


}