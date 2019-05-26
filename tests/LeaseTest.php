<?php


namespace slaveMarket;


use PHPUnit\Framework\TestCase;
use slaveMarket\classes\Lease;
use slaveMarket\classes\LeaseRequest;
use slaveMarket\interfaces\LeaseAgreementRepository;
use slaveMarket\interfaces\MastersRepository;
use slaveMarket\interfaces\SlavesRepository;
use slaveMarket\models\LeaseAgreement;
use slaveMarket\models\Master;
use slaveMarket\models\Slave;

class LeaseTest extends TestCase
{

    public function testLeaseWhenSlaveExist()
    {
        $firstMaster = new Master(1, 'Хозяин №1', false);
        $secondMaster = new Master(2, 'Хозяин №2', false);

        $mastersRepository = $this->prophesize(MastersRepository::class);
        foreach ([$firstMaster, $secondMaster] as $master) {
            $mastersRepository->findById($master->getId())->willReturn($master);
        }
        $mastersRepositoryMock = $mastersRepository->reveal();

        $slave = new Slave(1, 'Раб №1', 10);
        $slaveRepository = $this->prophesize(SlavesRepository::class);
        $slaveRepository->findById($slave->getId())->willReturn($slave);
        $slaveRepositoryMock = $slaveRepository->reveal();


        $leaseAgreement = new LeaseAgreement($firstMaster->getId(), $slave->getId(), 90, '2019-05-20 01:00:00', '2019-05-25 12:00:00');

        $leaseAgreementRepository = $this->prophesize(LeaseAgreementRepository::class);
        $leaseAgreementRepository->getForSlave($slave->getId(), '2019-05-21 01:00:00', '2019-05-23 12:00:00')->willReturn($leaseAgreement);
        $leaseAgreementRepositoryMock = $leaseAgreementRepository->reveal();

        $leaseRequest = new LeaseRequest($secondMaster->getId(), $slave->getId(), '2019-05-21 01:00:00', '2019-05-23 12:00:00');

        $lease = new Lease($mastersRepositoryMock, $slaveRepositoryMock, $leaseAgreementRepositoryMock);

        $response = $lease->run($leaseRequest);

        $expectedErrors = ['Раб Раб №1 занят. Вы не можете арендовать раба в период с 2019-05-20 01:00:00 по 2019-05-25 12:00:00'];

        $this->assertArraySubset($expectedErrors, $response->getErrors());
        $this->assertNull($response->getLeaseAgreement());
    }

    public function testLeaseWhenSlaveExistPartial()
    {
        $firstMaster = new Master(1, 'Хозяин №1', false);
        $secondMaster = new Master(2, 'Хозяин №2', false);

        $mastersRepository = $this->prophesize(MastersRepository::class);
        foreach ([$firstMaster, $secondMaster] as $master) {
            $mastersRepository->findById($master->getId())->willReturn($master);
        }
        $mastersRepositoryMock = $mastersRepository->reveal();

        $slave = new Slave(1, 'Раб №1', 10);
        $slaveRepository = $this->prophesize(SlavesRepository::class);
        $slaveRepository->findById($slave->getId())->willReturn($slave);
        $slaveRepositoryMock = $slaveRepository->reveal();


        $leaseAgreement = new LeaseAgreement($firstMaster->getId(), $slave->getId(), 90, '2019-05-20 01:00:00', '2019-05-25 12:00:00');

        $leaseAgreementRepository = $this->prophesize(LeaseAgreementRepository::class);
        $leaseAgreementRepository->getForSlave($slave->getId(), '2019-05-21 01:00:00', '2019-05-26 12:00:00')
            ->willReturn($leaseAgreement);
        $leaseAgreementRepositoryMock = $leaseAgreementRepository->reveal();

        $leaseRequest = new LeaseRequest($secondMaster->getId(), $slave->getId(), '2019-05-21 01:00:00', '2019-05-26 12:00:00');

        $lease = new Lease($mastersRepositoryMock, $slaveRepositoryMock, $leaseAgreementRepositoryMock);

        $response = $lease->run($leaseRequest);

        $expectedErrors = ['Раб Раб №1 занят. Вы не можете арендовать раба в период с 2019-05-20 01:00:00 по 2019-05-25 12:00:00'];

        $this->assertArraySubset($expectedErrors, $response->getErrors());
        $this->assertNull($response->getLeaseAgreement());
    }

    public function testLeaseWhenSlaveExistPartial2()
    {
        $firstMaster = new Master(1, 'Хозяин №1', false);
        $secondMaster = new Master(2, 'Хозяин №2', false);

        $mastersRepository = $this->prophesize(MastersRepository::class);
        foreach ([$firstMaster, $secondMaster] as $master) {
            $mastersRepository->findById($master->getId())->willReturn($master);
        }
        $mastersRepositoryMock = $mastersRepository->reveal();

        $slave = new Slave(1, 'Раб №1', 10);
        $slaveRepository = $this->prophesize(SlavesRepository::class);
        $slaveRepository->findById($slave->getId())->willReturn($slave);
        $slaveRepositoryMock = $slaveRepository->reveal();


        $leaseAgreement = new LeaseAgreement($firstMaster->getId(), $slave->getId(), 90, '2019-05-20 01:00:00', '2019-05-25 12:00:00');

        $leaseAgreementRepository = $this->prophesize(LeaseAgreementRepository::class);
        $leaseAgreementRepository->getForSlave($slave->getId(), '2019-05-10 01:00:00', '2019-05-22 12:00:00')
            ->willReturn($leaseAgreement);
        $leaseAgreementRepositoryMock = $leaseAgreementRepository->reveal();

        $leaseRequest = new LeaseRequest($secondMaster->getId(), $slave->getId(), '2019-05-10 01:00:00', '2019-05-22 12:00:00');

        $lease = new Lease($mastersRepositoryMock, $slaveRepositoryMock, $leaseAgreementRepositoryMock);

        $response = $lease->run($leaseRequest);

        $expectedErrors = ['Раб Раб №1 занят. Вы не можете арендовать раба в период с 2019-05-20 01:00:00 по 2019-05-25 12:00:00'];

        $this->assertArraySubset($expectedErrors, $response->getErrors());
        $this->assertNull($response->getLeaseAgreement());
    }

    public function testLeaseWhenSlaveExistAndMasterVip()
    {
        $firstMaster = new Master(1, 'Хозяин №1', false);
        $secondMaster = new Master(2, 'Хозяин №2', true);

        $mastersRepository = $this->prophesize(MastersRepository::class);
        foreach ([$firstMaster, $secondMaster] as $master) {
            $mastersRepository->findById($master->getId())->willReturn($master);
        }
        $mastersRepositoryMock = $mastersRepository->reveal();

        $slave = new Slave(1, 'Раб №1', 10);
        $slaveRepository = $this->prophesize(SlavesRepository::class);
        $slaveRepository->findById($slave->getId())->willReturn($slave);
        $slaveRepositoryMock = $slaveRepository->reveal();


        $leaseAgreement = new LeaseAgreement($firstMaster->getId(), $slave->getId(), 90, '2019-05-20 01:00:00', '2019-05-25 12:00:00');

        $leaseAgreementRepository = $this->prophesize(LeaseAgreementRepository::class);
        $leaseAgreementRepository->getForSlave($slave->getId(), '2019-05-21 01:00:00', '2019-05-23 12:00:00')->willReturn($leaseAgreement);
        $leaseAgreementRepositoryMock = $leaseAgreementRepository->reveal();

        $leaseRequest = new LeaseRequest($secondMaster->getId(), $slave->getId(), '2019-05-21 01:00:00', '2019-05-23 12:00:00');

        $lease = new Lease($mastersRepositoryMock, $slaveRepositoryMock, $leaseAgreementRepositoryMock);

        $response = $lease->run($leaseRequest);

        $this->assertEmpty($response->getErrors());
        $this->assertNotNull($response->getLeaseAgreement());
        $this->assertTrue($response->getLeaseAgreement() instanceof LeaseAgreement);
    }

    public function testLeaseWhenSlaveExistAndAllMastersVip()
    {
        $firstMaster = new Master(1, 'Хозяин №1', true);
        $secondMaster = new Master(2, 'Хозяин №2', true);

        $mastersRepository = $this->prophesize(MastersRepository::class);
        foreach ([$firstMaster, $secondMaster] as $master) {
            $mastersRepository->findById($master->getId())->willReturn($master);
        }
        $mastersRepositoryMock = $mastersRepository->reveal();

        $slave = new Slave(1, 'Раб №1', 10);
        $slaveRepository = $this->prophesize(SlavesRepository::class);
        $slaveRepository->findById($slave->getId())->willReturn($slave);
        $slaveRepositoryMock = $slaveRepository->reveal();


        $leaseAgreement = new LeaseAgreement($firstMaster->getId(), $slave->getId(), 90, '2019-05-20 01:00:00', '2019-05-25 12:00:00');

        $leaseAgreementRepository = $this->prophesize(LeaseAgreementRepository::class);
        $leaseAgreementRepository->getForSlave($slave->getId(), '2019-05-21 01:00:00', '2019-05-23 12:00:00')->willReturn($leaseAgreement);
        $leaseAgreementRepositoryMock = $leaseAgreementRepository->reveal();

        $leaseRequest = new LeaseRequest($secondMaster->getId(), $slave->getId(), '2019-05-21 01:00:00', '2019-05-23 12:00:00');

        $lease = new Lease($mastersRepositoryMock, $slaveRepositoryMock, $leaseAgreementRepositoryMock);

        $response = $lease->run($leaseRequest);

        $expectedErrors = ['Раб Раб №1 занят. Вы не можете арендовать раба в период с 2019-05-20 01:00:00 по 2019-05-25 12:00:00'];

        $this->assertArraySubset($expectedErrors, $response->getErrors());
        $this->assertNull($response->getLeaseAgreement());
    }

    public function testLeaseWhenSlaveNotBusyAndExistsLeaseAgreement()
    {
        $firstMaster = new Master(1, 'Хозяин №1', false);
        $secondMaster = new Master(2, 'Хозяин №2', false);

        $mastersRepository = $this->prophesize(MastersRepository::class);
        foreach ([$firstMaster, $secondMaster] as $master) {
            $mastersRepository->findById($master->getId())->willReturn($master);
        }
        $mastersRepositoryMock = $mastersRepository->reveal();

        $slave = new Slave(1, 'Раб №1', 10);
        $slaveRepository = $this->prophesize(SlavesRepository::class);
        $slaveRepository->findById($slave->getId())->willReturn($slave);
        $slaveRepositoryMock = $slaveRepository->reveal();


        $leaseAgreement = new LeaseAgreement($firstMaster->getId(), $slave->getId(), 90, '2019-05-20 01:00:00', '2019-05-25 12:00:00');

        $leaseAgreementRepository = $this->prophesize(LeaseAgreementRepository::class);
        $leaseAgreementRepository->getForSlave($slave->getId(), '2019-05-10 01:00:00', '2019-05-12 12:00:00')
            ->willReturn($leaseAgreement);
        $leaseAgreementRepositoryMock = $leaseAgreementRepository->reveal();

        $leaseRequest = new LeaseRequest($secondMaster->getId(), $slave->getId(), '2019-05-10 01:00:00', '2019-05-12 12:00:00');

        $lease = new Lease($mastersRepositoryMock, $slaveRepositoryMock, $leaseAgreementRepositoryMock);

        $response = $lease->run($leaseRequest);

        $this->assertEmpty($response->getErrors());
        $this->assertNotNull($response->getLeaseAgreement());
        $this->assertTrue($response->getLeaseAgreement() instanceof LeaseAgreement);
    }

    public function testLeaseWhenSlaveNotExistsLeaseAgreement()
    {
        $firstMaster = new Master(1, 'Хозяин №1', false);
        $secondMaster = new Master(2, 'Хозяин №2', false);

        $mastersRepository = $this->prophesize(MastersRepository::class);
        foreach ([$firstMaster, $secondMaster] as $master) {
            $mastersRepository->findById($master->getId())->willReturn($master);
        }
        $mastersRepositoryMock = $mastersRepository->reveal();

        $slave = new Slave(1, 'Раб №1', 10);
        $slaveRepository = $this->prophesize(SlavesRepository::class);
        $slaveRepository->findById($slave->getId())->willReturn($slave);
        $slaveRepositoryMock = $slaveRepository->reveal();

        $leaseAgreementRepository = $this->prophesize(LeaseAgreementRepository::class);
        $leaseAgreementRepository->getForSlave($slave->getId(), '2019-05-10 01:00:00', '2019-05-12 12:00:00')
            ->willReturn(null);
        $leaseAgreementRepositoryMock = $leaseAgreementRepository->reveal();

        $leaseRequest = new LeaseRequest($secondMaster->getId(), $slave->getId(), '2019-05-10 01:00:00', '2019-05-12 12:00:00');

        $lease = new Lease($mastersRepositoryMock, $slaveRepositoryMock, $leaseAgreementRepositoryMock);

        $response = $lease->run($leaseRequest);

        $this->assertEmpty($response->getErrors());
        $this->assertNotNull($response->getLeaseAgreement());
        $this->assertTrue($response->getLeaseAgreement() instanceof LeaseAgreement);
    }


}