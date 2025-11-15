<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Controller\Api\Contract;

use App\Application\User\Transformer\Service\GetUserPortfolioService;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
