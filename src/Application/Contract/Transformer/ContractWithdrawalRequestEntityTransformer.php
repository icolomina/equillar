<?php

namespace App\Application\Contract\Transformer;

use App\Entity\Investment\ContractInvestment;
use App\Entity\Investment\ContractInvestmentWithdrawalRequest;
use App\Presentation\Contract\DTO\Input\ContractInvestmentRequestWithdrawalDtoInput;
use App\Presentation\Contract\DTO\Output\ContractInvestmentWithdrawalRequestDtoOutput;

class ContractInvestmentWithdrawalRequestEntityTransformer
{
    public function __construct(
        private readonly ContractEntityTransformer $contractInvestmentEntityTransformer
    ){}

    public function fromRequestWithdrawalDtoToEntity(ContractInvestment $contractInvestment, ContractInvestmentRequestWithdrawalDtoInput $contractInvestmentRequestWithdrawalDtoInput): ContractInvestmentWithdrawalRequest
    {
        $requestWithdrawal = new ContractInvestmentWithdrawalRequest();
        $requestWithdrawal->setContractInvestment($contractInvestment);
        $requestWithdrawal->setRequestedAt(new \DateTimeImmutable());
        $requestWithdrawal->setRequestedAmount($contractInvestmentRequestWithdrawalDtoInput->requestedAmount);

        return $requestWithdrawal;
    }

    public function fromEntityToOutputDto(ContractInvestmentWithdrawalRequest $contractInvestmentWithdrawalRequest): ContractInvestmentWithdrawalRequestDtoOutput
    {
        $contractInvestmentDto = $this->contractInvestmentEntityTransformer->fromEntityToOutputDto($contractInvestmentWithdrawalRequest->getContractInvestment());
        return new ContractInvestmentWithdrawalRequestDtoOutput(
            $contractInvestmentDto,
            $contractInvestmentWithdrawalRequest->getRequestedAt()->format('Y-m-d H:i'),
            $contractInvestmentWithdrawalRequest->getRequestedAmount(),
            $contractInvestmentWithdrawalRequest->getStatus(),
            $contractInvestmentWithdrawalRequest->getHash()
        );
    }
}
