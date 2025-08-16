<?php

namespace App\Controller\Api\Contract;

use App\Application\User\Transformer\Service\GetUserPortfolioService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\User;

#[Route('/api/v1/user')]
class UserPorftolioController extends AbstractController
{
    #[Route('/get-portfolio', name: 'get_user_portfolio', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getUserPortfolio(GetUserPortfolioService $getUserPortfolioService): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        return $this->json($getUserPortfolioService->getPortfolio($user));
    }
}
