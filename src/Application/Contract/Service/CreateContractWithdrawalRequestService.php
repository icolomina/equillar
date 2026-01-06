<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Application\Contract\Service;

use App\Application\Contract\Transformer\ContractWithdrawalRequestEntityTransformer;
use App\Entity\Contract\Contract;
use App\Entity\User;
use App\Persistence\PersistorInterface;
use App\Presentation\Contract\DTO\Input\ContractRequestWithdrawalDtoInput;
use App\Presentation\Contract\DTO\Output\ContractWithdrawalRequestDtoOutput;
use App\Security\Uri\UrlSigner;
use Symfony\Component\Routing\RouterInterface;

class CreateContractWithdrawalRequestService
{
    public function __construct(
        private readonly ContractWithdrawalRequestEntityTransformer $contractWithdrawalRequestEntityTransformer,
        private readonly UrlSigner $urlSigner,
        private readonly PersistorInterface $persistorInterface,
        private readonly RouterInterface $router,
    ) {
    }

    public function createContractWithdrawalRequest(Contract $contract, User $user, ContractRequestWithdrawalDtoInput $contractRequestWithdrawalDtoInput): ContractWithdrawalRequestDtoOutput
    {
        $requestWithdrawal = $this->contractWithdrawalRequestEntityTransformer->fromRequestWithdrawalDtoToEntity(
            $contract,
            $user,
            $contractRequestWithdrawalDtoInput
        );


        $this->persistorInterface->persistAndFlush($requestWithdrawal);

        /**
         * Generate signed URL for confirming the withdrawal request. This is not in use right now but will be used in the email sent to the user in next versions.
         */
        $requestWithdrawalUrl = $this->urlSigner->signUrl($this->router->generate('em_get_confirm_withdrawal', ['id' => $requestWithdrawal->getId()], RouterInterface::ABSOLUTE_URL));
        $requestWithdrawal->setConfirmUrl($requestWithdrawalUrl);
        $this->persistorInterface->persistAndFlush($requestWithdrawal);

        return $this->contractWithdrawalRequestEntityTransformer->fromEntityToOutputDto($requestWithdrawal);
    }
}
