<?php

namespace App\Application\UserContract\Service;

use App\Application\UserContract\Transformer\UserContractEntityTransformer;
use App\Entity\Contract\UserContract;
use App\Presentation\UserContract\DTO\Output\UserContractDtoOutput;

class UserContractEditService
{
    public function __construct(
        private readonly UserContractPaymentsCalendarService $userContractPaymentsCalendarService,
        private readonly UserContractEntityTransformer $userContractEntityTransformer,
    ) {
    }

    public function editUserContract(UserContract $userContract): UserContractDtoOutput
    {
        $calendar = $this->userContractPaymentsCalendarService->generatePaymentsCalendar($userContract);

        return $this->userContractEntityTransformer->fromEntityToOutputDto($userContract, $calendar->getCalendar());
    }
}
