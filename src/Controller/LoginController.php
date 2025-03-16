<?php

namespace App\Controller;

use App\Api\User\RegisterUserService;
use App\Application\Security\Token\TokenEncoder;
use App\Entity\User;
use App\Presentation\User\DTO\Input\RegisterUserDtoInput;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/register-user-data', name: 'api_register_user', methods: ['POST'])]
    public function postRegisterUserData(#[MapRequestPayload] RegisterUserDtoInput $registerUserDtoInput, RegisterUserService $registerUserService): JsonResponse
    {
        $registerUserService->registerUser($registerUserDtoInput);
        return new JsonResponse(null, 204);
    }

    #[Route('/register', name: 'get_register_page', methods: ['GET'])]
    public function getRegisterPage(): Response
    {
        return $this->render('register.html.twig');
    }

    #[Route('/login', name: 'get_api_login', methods: ['GET'])]
    public function getLogin(string $webserverEndpoint): Response 
    {
        return $this->render('SignIn.html.twig', ['webserverEndpoint' => $webserverEndpoint]);
    }

    #[Route('/do-login', name: 'post_api_login', methods: ['POST'])]
    public function postLogin(#[CurrentUser] ?User $user, TokenEncoder $tokenEncoder): Response
    {
        if (null === $user) {
            return $this->json([
              'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'user'  => $user->getUserIdentifier(),
            'token' => $tokenEncoder->encode($user),
            'role' => $user->getRoles()[0]
        ]);
    }

    #[Route(path: '/api/v1/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
