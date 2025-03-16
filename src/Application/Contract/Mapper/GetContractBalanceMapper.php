<?php

namespace App\Application\Contract\Mapper;

use App\Domain\Utils\Math\I128Handler;
use App\Entity\Investment\ContractInvestmentBalance;

class GetContractBalanceMapper
{
    public function __construct(
        private readonly I128Handler $i128Handler
    ){}

    public function mapToEntity(array $trxResult, ContractInvestmentBalance $contractInvestmentBalance): void
    {
        $decimals  = $contractInvestmentBalance->getContractInvestment()->getToken()->getDecimals();

        foreach($trxResult as $key => $value) {
            $result = $this->i128Handler->fromI128ToPhpFloat($value->getLo(), $value->getHi(), $decimals);
            match($key) {
                'reserve_fund' => $contractInvestmentBalance->setReserveFund($result),
                'project' => $contractInvestmentBalance->setAvailable($result),
                'comission' => $contractInvestmentBalance->setComission($result)
            };
        }
    }
}
