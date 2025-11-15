<?php
// Copyright 2025 The Equillar Authors. All rights reserved.
// Use of this source code is governed by a license that can be
// found in the LICENSE file.

namespace App\Controller\Api;

use App\Application\Token\Service\GetTokensService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/v1/token')]
#[IsGranted(new Expression('is_granted("ROLE_ADMIN") or is_granted("ROLE_COMPANY")'))]
class TokenController extends AbstractController
{
    #[Route('/get-available-tokens', name: 'api_get_available_tokens', methods: ['GET'])]
    public function getTokens(GetTokensService $getTokensService): JsonResponse
    {
        return $this->json($getTokensService->getTokens());
    }
}
