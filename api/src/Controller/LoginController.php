<?php

namespace App\Controller;

use App\Service\AuthService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends AbstractController
{
    public function __construct(private AuthService $authService)
    {
    }

    public function auth(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $responseData = [];

        if (json_last_error() !== JSON_ERROR_NONE) {
            $responseData = ['error' => "Bad JSON data."];
        }

        if (empty($responseData) && !isset($data['login']) || !isset($data['password'])) {
            $responseData = ['error' => "Login or password does not set"];
        }

        if (empty($responseData) && !$user = $this->authService->getUser($data)) {
            $responseData = ['error' => "Login or password not correct."];
        }

        if (isset($user) && $user) {
            $responseData = ["user" => [
                "id"     => $user->getId(),
                "login"  => $user->getLogin(),
                "points" => $user->getPoints(),
            ]];
        }

        return new JsonResponse($responseData);
    }
}
