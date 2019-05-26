<?php

namespace slaveMarket\helpers;

use slaveMarket\classes\BaseObject;
use slaveMarket\models\LeaseAgreement;
use slaveMarket\models\Master;

/**
 * Class LeaseHelper
 * @package slaveMarket\helpers
 */
class LeaseHelper extends BaseObject
{

    /**
     * @Description Max work hours for slave
     * @var int
     */
    protected $maxWorkHours;

    /**
     * @Description Start work day for slave
     * @var int
     */
    protected $hourStartWorkDay;

    /**
     * @Description End work day for slave
     * @var int
     */
    protected $hourEndWorkDay;

    public function __construct()
    {
        parent::__construct();
        $this->maxWorkHours = getenv('MAX_WORK_HOURS_SLAVE');
        $this->hourStartWorkDay = getenv('HOUR_START_WORK_DAY_SLAVE');
        $this->hourEndWorkDay = getenv('HOUR_END_WORK_DAY_SLAVE');
    }

    /**
     * @param $dateTime
     *
     * @return bool
     */
    public function validateTimeFormat(string $dateTime): bool
    {
        return (bool)\DateTime::createFromFormat('Y-m-d H:i:s', $dateTime);
    }

    /**
     * @param string $timeFrom in format Y-m-d H:i:s
     * @param string $timeTo in format Y-m-d H:i:s
     *
     * @return mixed
     */
    public function getFullLeaseDays(string $timeFrom, string $timeTo)
    {
        $timeFrom = \DateTime::createFromFormat("Y-m-d H:i:s", $timeFrom);
        $timeTo = \DateTime::createFromFormat("Y-m-d H:i:s", $timeTo);

        return $timeFrom->diff($timeTo)->days;
    }

    /**
     * @param Master $master
     * @param LeaseAgreement $leaseAgreement
     * @param string $desiredTimeFrom in format Y-m-d H:i:s
     * @param string $desiredTimeTo in format Y-m-d H:i:s
     *
     * @return null|string
     */
    public function checkAllowedLeaseTimeForMaster(Master $master, Master $masterHavingLeaseAgreement, LeaseAgreement $leaseAgreement, string $desiredTimeFrom, string $desiredTimeTo): ?string
    {

        $error = null;

        $startLeaseTime = strtotime($leaseAgreement->getStartLeaseTime());
        $stopLeaseTime = strtotime($leaseAgreement->getStopLeaseTime());


        $startHour = date("H", strtotime($desiredTimeFrom));
        $startTimeFrom = strtotime(date("Y-m-d H:00:00", strtotime($desiredTimeFrom)));
        $stopHour = date("H", strtotime($desiredTimeTo));
        if ($startHour === $stopHour) {
            $stopTimeFrom = strtotime(date("Y-m-d H:00:00", strtotime($desiredTimeTo) + 3600));
        } else {
            $stopTimeFrom = strtotime(date("Y-m-d H:00:00", strtotime($desiredTimeTo)));
        }

        if (($startLeaseTime < $startTimeFrom && $startTimeFrom < $stopLeaseTime) || ($startLeaseTime < $stopTimeFrom && $stopTimeFrom < $stopLeaseTime)) {
            $error = 'Вы не можете арендовать раба в период с ' . $leaseAgreement->getStartLeaseTime() . ' по ' . $leaseAgreement->getStopLeaseTime();
        }

        if ($error && !$masterHavingLeaseAgreement->isVip() && $master->isVip()) {
            return null;
        }

        return $error;
    }

    /**
     * @param float $pricePerHour
     * @param string $startLeaseTime
     * @param string $stopLeaseTime
     *
     * @return float
     */
    public function getLeasePrice(float $pricePerHour, string $startLeaseTime, string $stopLeaseTime): float
    {

        $fullDays = $this->getFullLeaseDays($startLeaseTime, $stopLeaseTime);
        $startHour = (int)date("H", strtotime($startLeaseTime));
        $stopHour = (int)date("H", strtotime($stopLeaseTime));
        if ($startHour === $stopHour) {
            $stopHour++;
        }

        if ($fullDays === 0) {
            $totalHour = ($stopHour - $startHour);
        } elseif ($fullDays === 1) {
            $totalHour = ($this->hourEndWorkDay - $startHour) + ($stopHour - $this->hourStartWorkDay);
        } else {
            $totalHour = ($this->hourEndWorkDay - $startHour) + ($stopHour - $this->hourStartWorkDay) + (($fullDays - 1) * $this->maxWorkHours);
        }

        return $pricePerHour * $totalHour;
    }

    /**
     * @param string $desiredTimeFrom in format Y-m-d H:i:s
     * @param string $desiredTimeTo in format Y-m-d H:i:s
     *
     * @return null|string
     */
    public
    function checkMaxWorkTimeSlave(
        string $desiredTimeFrom, string $desiredTimeTo
    ): ?string
    {

        $fullLeaseDay = $this->getFullLeaseDays($desiredTimeFrom, $desiredTimeTo);

        if ($fullLeaseDay == 0) {
            $error = $this->getErrorMaxWorkTime((int)date("H", strtotime($desiredTimeFrom)), (int)date("H", strtotime($desiredTimeTo)));

        } elseif (!$error = $this->getErrorMaxWorkTime((int)date("H", strtotime($desiredTimeFrom)), $this->hourEndWorkDay)) {
            $error = $this->getErrorMaxWorkTime($this->hourStartWorkDay, (int)date("H", strtotime($desiredTimeTo)));
        }

        return $error;
    }

    /**
     * @param int $hourFrom
     * @param int $hourTo
     *
     * @return null|string
     */
    private
    function getErrorMaxWorkTime(
        int $hourFrom, int $hourTo
    ): ?string
    {
        if ($hourTo === $hourFrom) {
            $hourTo++;
        }
        if ($hourTo - $hourFrom > $this->maxWorkHours) {
            return 'Раб не может работать больше ' . $this->maxWorkHours . ' часов';
        }

        return null;
    }

}