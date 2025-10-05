<?php

namespace App\Controller;

use App\Application\Contract\Service\ContractWithdrawalConfirmationService;
use App\Entity\Contract\ContractWithdrawalRequest;
use App\Security\Uri\UrlSigner;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ExternaController extends AbstractController
{
    #[Route('/em/request-withdrawal/{id}/confirm', name: 'em_get_confirm_withdrawal', methods: ['GET'])]
    public function confirmWithdrawal(ContractWithdrawalRequest $contractWithdrawalRequest, UrlSigner $urlSigner, ContractWithdrawalConfirmationService $contractWithdrawalConfirmationService,
        Request $request): Response
    {
        $urlSigner->validateRequestSignature($request);
        $contractWithdrawalConfirmationService->confirmWithdrawal($contractWithdrawalRequest);

        return new RedirectResponse($this->generateUrl('get_withdrawal_confirmed'));
    }
}
