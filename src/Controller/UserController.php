<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use FOS\RestBundle\Controller\Annotations as Rest;

class UserController extends AbstractController
{
    /**
     * Liste des users
     * 
     * @OA\Get(
     *      path="/users",
     *      tags={"Users"},
     *      security={"bearer"},
     *      @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="Le numéro de la page à récupérer",
     *          required=false,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Liste des users",
     *          @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/User")),
     *      ),
     *      @OA\Response(
     *          response="404",
     *          description="Not found",
     *      ),
     *      @OA\Response(
     *          response="401",
     *          description="JWT not found or expired",
     *      ),
     * )
     */
    public function liste(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ProductController.php',
        ]);
    }

    /**
     * Détail d'un user
     * 
     * @Rest\Get(path="/api/users/{id}", name="api_user_details")
     * @Rest\View(statusCode= 200)
     * @OA\Get(
     *      path="/users/{id}",
     *      security={"bearer"},
     *      tags={"Users"},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID du user",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Détail d'un user",
     *          @OA\JsonContent(ref="#/components/schemas/User"),
     *      ),
     *      @OA\Response(
     *          response="404",
     *          description="Not found",
     *      ),
     *      @OA\Response(
     *          response="401",
     *          description="JWT not found or expired",
     *      ),
     * )
     */
    public function details(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ProductController.php',
        ]);
    }

    /**
     * Créer un user
     * 
     * @OA\Post(
     *      path="/users",
     *      tags={"Users"},
     *      security={"bearer"},
     *      @OA\RequestBody(
     *          request="AddUser",
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"username", "email", "password"},
     *                  @OA\Property(type="string", property="username"),
     *                  @OA\Property(type="string", property="email"),
     *                  @OA\Property(type="string", property="password")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response="201",
     *          description="Creation user",
     *          @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/User")),
     *      ),
     *      @OA\Response(
     *          response="404",
     *          description="Not found",
     *      ),
     *      @OA\Response(
     *          response="401",
     *          description="JWT not found or expired",
     *      ),
     * )
     */
    public function add(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ProductController.php',
        ]);
    }
}
