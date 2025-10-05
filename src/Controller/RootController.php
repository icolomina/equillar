<?php

namespace App\Controller;

use App\Blockchain\Stellar\Soroban\Server\ServerLoaderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RootController extends AbstractController
{
    #[Route('', name: 'get_app_redir', methods: ['GET'])]
    #[Route('/app', name: 'get_app', methods: ['GET'])]
    #[Route('/login', name: 'get_login', methods: ['GET'])]
    #[Route('/withdrawal-confirmed', name: 'get_withdrawal_confirmed', methods: ['GET'])]
    #[Route('/app/{seg1}', name: 'get_app_seg1', methods: ['GET'])]
    #[Route('/app/{seg1}/{seg2}', name: 'get_app_seg2', methods: ['GET'])]
    #[Route('/app/{seg1}/{seg2}/{seg3}', name: 'get_app_seg3', methods: ['GET'])]
    #[Route('/app/{seg1}/{seg2}/{seg3}/{seg4}', name: 'get_app_seg4', methods: ['GET'])]
    public function getApp(?string $seg1, ?string $seg2, ?string $seg3, ?string $seg4, ServerLoaderService $serverLoaderService, string $webserverEndpoint): Response
    {
        return $this->render('App.html.twig', [
            'sorobanNetworkPassphrase' => $serverLoaderService->getSorobanNetwork()->getNetworkPassphrase(),
            'sorobanRpcUrl' => $serverLoaderService->getSorobanRpcUrl(),
            'webserverEndpoint' => $webserverEndpoint,
        ]);
    }
}
