<?php

namespace App\Command\Contract;

use App\Application\Contract\Service\Blockchain\ContractReserveFundContributionTransferService;
use App\Application\Contract\Service\ReceiveReserveFundContributionService;
use App\Blockchain\Stellar\Account\StellarAccountLoader;
use App\Domain\Contract\Service\ContractReserveFundContributonIdEncoder;
use App\Domain\Token\Service\TokenNormalizer;
use App\Persistence\Contract\ContractReserveFundContributionStorageInterface;
use Soneso\StellarSDK\Memo;
use Soneso\StellarSDK\Responses\Operations\PaymentOperationResponse;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name : 'app:contract:reserve-fund-contributions:check-payments'
)]
class ContractCheckReserveFundContributionsCommand extends Command
{
    public function __construct(
        private readonly StellarAccountLoader $stellarAccountLoader,
        private readonly ContractReserveFundContributonIdEncoder $contractReserveFundContributonIdEncoder,
        private readonly ContractReserveFundContributionStorageInterface $contractReserveFundContributionStorage,
        private readonly TokenNormalizer $tokenNormalizer,
        private readonly ReceiveReserveFundContributionService $receiveReserveFundContributionService,
        private readonly ContractReserveFundContributionTransferService $contractReserveFundContributionTransferService
    ){
        parent::__construct();
    }

    public function configure(): void
    {
        $this
            ->addOption('id', null, InputOption::VALUE_OPTIONAL, 'Contribution ID', null)
        ;

    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $contributonId = $input->getOption('id');
        $sdk = $this->stellarAccountLoader->getSdk();

        $io->title('Getting the last 10 payments ...... ');
        $operationsResponse = $sdk
            ->payments()
            ->includeTransactions(true)
            ->forAccount($this->stellarAccountLoader->getAccount()->getAccountId())
            ->order('desc')
            ->limit(10)
            ->execute()
        ;

        foreach($operationsResponse->getOperations() as $payment) {

            if($payment->isTransactionSuccessful() && $payment instanceof PaymentOperationResponse) {
                $transaction = $payment->getTransaction();
                $memo        = $transaction->getMemo();
                
                if($memo->getType() === Memo::MEMO_TYPE_TEXT) {
                    $decodedId = $this->contractReserveFundContributonIdEncoder->decodeId($memo->getValue());

                    if($contributonId && $decodedId !== $contributonId) {

                        $io->writeln(sprintf('Looking for contribution id %s but %s retrieved. Continue ...', $contributonId, $decodedId ));
                        continue;
                    }

                    $contractReserveFundContribution = $this->contractReserveFundContributionStorage->getByUuidAndStatus($decodedId, 'CREATED');
                    $tokenDecimals = $contractReserveFundContribution->getContract()->getToken()->getDecimals();
                    $normalizedAmount = $this->tokenNormalizer->normalizeTokenValue($contractReserveFundContribution->getAmount(), $tokenDecimals);
                    if( (float)$payment->getAmount() >= (float)$normalizedAmount->toPhp($tokenDecimals)){

                        $io->writeln(sprintf('Processing Received payment: Total: %s - Contract: %s', $payment->getAmount(), $contractReserveFundContribution->getContract()->getLabel()));
                        $this->receiveReserveFundContributionService->setReserveFundContributionAsReceived($contractReserveFundContribution);
                        $this->contractReserveFundContributionTransferService->processReserveFundContribution($contractReserveFundContribution);
                        $io->writeln(sprintf('Received payment processed: Total: %s - Contract: %s', $payment->getAmount(), $contractReserveFundContribution->getContract()->getLabel()));
                        $io->writeln(' -------------------------------------------------------------------------------------------- ');
                    }
                    else {
                        $io->writeln(sprintf('Payment received %s is lower than expected %s', $payment->getAmount(), $normalizedAmount->toPhp($tokenDecimals)));
                    }
                }
                else {
                    $io->writeln(sprintf('Transaction %s memo type is invalid. Required %s but got %s', $payment->getTransactionHash(), Memo::MEMO_TYPE_TEXT, $memo->getType() ));
                }
            }
            else {
                $io->writeln(sprintf('Transaction %s is not successful or is not a payment transaction', $payment->getTransactionHash() ));
            }
        }

        return Command::SUCCESS;
    }
}
