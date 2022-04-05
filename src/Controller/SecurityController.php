<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use FOS\RestBundle\Controller\Annotations as Rest;

class SecurityController extends AbstractController
{
    /**
     *  Envoi un JWT token
     * 
     * @Rest\Post(path="/api/login_check", name="api_login")
     * @Rest\View(statusCode= 200)
     * @OA\Post(
     *     path="/login_check",
     *     tags={"Authenticate"},
     *     @OA\Response(
     *          response="200",
     *          description="Authentication",
     *     @OA\JsonContent(ref="#/components/schemas/Client"),
     *     ),
     * @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         @OA\Property(property="username", description="username du client", type="string", example="votrePseudo"),
     *         @OA\Property(property="password", description="Password du client", type="string", format="password", example="votrePassword")
     *       )
     *     )
     *   ),
     * @OA\Response(response=404, description="Not found"),
     * @OA\Response(response=401, description="Invalid credentials"),
     * )
     * 
     * @return JsonResponse
     */
    public function api_login()
    {
        // $user = $this->getUser();

        // return new JsonResponse([
        //     'username' => $user->getUserIdentifier(),
        //     'roles' => $user->getRoles(),
        // ]);
    }
}
