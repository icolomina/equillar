<?php

namespace App\Tests\Application\Contract\Service\Blockchain;

use App\Application\Contract\Service\Blockchain\ContractWithdrawalApprovalService;
use App\Blockchain\Stellar\Soroban\ScContract\Operation\ContractWithdrawalOperation;
use App\Entity\Contract\ContractWithdrawalApproval;
use App\Entity\Contract\ContractWithdrawalRequest;
use App\Persistence\Contract\ContractWithdrawalApprovalStorageInterface;
use App\Persistence\PersistorInterface;
use App\Tests\EntityGenerator;
use App\Tests\GenerateXdrMapEntryMock;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Container\ContainerInterface;
use Soneso\StellarSDK\Soroban\Responses\GetTransactionResponse;
use Soneso\StellarSDK\Xdr\XdrSCVal;
use Soneso\StellarSDK\Xdr\XdrSCValType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class ContractWithdrawalServiceTest extends KernelTestCase
{
    use GenerateXdrMapEntryMock;

    private ContainerInterface $container;
    private PersistorInterface $persistor;
    private ContractWithdrawalApprovalStorageInterface $contractWithdrawalApprovalStorage;

    protected function setUp(): void
    {
        parent::setUp(); 

        self::bootKernel(); 
        $this->container = static::getContainer(); 
        $this->persistor = $this->container->get('test.App\Persistence\PersistorInterface');
        $this->contractWithdrawalApprovalStorage = $this->container->get('test.App\Persistence\Contract\ContractWithdrawalApprovalStorageInterface');
    }

    public function testContractWithdrawn(): void
    {
        $contractWithdrawalOperatonStub = $this->getMockBuilder(ContractWithdrawalOperation::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        
        $contractWithdrawalOperatonStub->method('projectWithdrawn')->willReturn($this->createTransactionResponseStub());
        $busStub = $this->getMockBuilder(MessageBusInterface::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $busStub->method('dispatch')->willReturn(new Envelope(new \stdClass()));

        /**
         * @var ContractWithdrawalService $contractWithdrawalServiceStub
         */
        $contractWithdrawalServiceStub = $this->getMockBuilder(ContractWithdrawalApprovalService::class)
            ->setConstructorArgs([
                $contractWithdrawalOperatonStub,
                $this->container->get('test.App\Domain\ScContract\Service\ScContractResultBuilder'),
                $this->container->get('test.App\Application\Contract\Transformer\ContractTransactionEntityTransformer'),
                $this->container->get('test.App\Application\Contract\Transformer\ContractWithdrawalApprovalEntityTransformer'),
                $this->persistor,
                $busStub
            ])
            ->onlyMethods([])
            ->getMock()
        ;

        $contractWithdrawalRequest = $this->loadContractWithdrawalRequest();
        $contractWithdrawalServiceStub->processProjectWithdrawal($contractWithdrawalRequest);
        $contractWithdrawalApproval = $this->contractWithdrawalApprovalStorage->getByWithdrawalRequest($contractWithdrawalRequest);

        $this->assertInstanceOf(ContractWithdrawalApproval::class, $contractWithdrawalApproval);
        $this->assertEquals('FUNDS_SENT', $contractWithdrawalApproval->getStatus());

    }

    private function createTransactionResponseStub(): MockObject
    {
        $xdrBoolResultMock = $this->getMockBuilder(XdrSCVal::class)
            ->setConstructorArgs([new XdrSCValType(XdrSCValType::SCV_BOOL)])
            ->getMock()
        ;
        
        $xdrBoolResultMock->method('getB')->willReturn(true);

        $transactionResponseStub = $this->getMockBuilder(GetTransactionResponse::class)->disableOriginalConstructor()->getMock();
        $transactionResponseStub->expects($this->once())->method('getResultValue')->willReturn($xdrBoolResultMock);
        $transactionResponseStub->expects($this->atLeastOnce())->method('getTxHash')->willReturn('886996787366366355553');
        $transactionResponseStub->expects($this->atLeastOnce())->method('getLedger')->willReturn(15986662548);
        
        return $transactionResponseStub;
    }

    private function loadContractWithdrawalRequest(): ContractWithdrawalRequest
    {
        $issuer         = EntityGenerator::createIssuer();
        $token          = EntityGenerator::createToken();
        $investor       = EntityGenerator::createInvestor();
        $investorWallet = EntityGenerator::createUserWallet($investor);
        $contract       = EntityGenerator::createActiveContract($issuer, $token);
        $withdrawalRequest = EntityGenerator::createContractWithdrawalRequest($issuer, $contract);


        $this->persistor->persistAndFlush([
            $token,
            $issuer,
            $investor,
            $investorWallet,
            $contract,
        ]);

        return $withdrawalRequest;
    }
}
