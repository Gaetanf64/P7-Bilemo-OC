<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class UserController extends AbstractController
{
    /**
     * Liste des users
     * 
     * @OA\Get(
     *      path="/users",
     *      @OA\Parameter(
     *          name="liste des users",
     *          in="query",
     *          description="Le nombre de users",
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
     * Créer un user
     * 
     * @OA\Post(
     *      path="/users",
     *      @OA\Parameter(
     *          name="addUser",
     *          in="query",
     *          description="Add User",
     *          required=false,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          request="AddUser",
     *          required=true,
     *          @OA\JsonContent(
     *              required={"username", "email", "password"},
     *              @OA\Property(type="string", property="username"),
     *              @OA\Property(type="string", property="email"),
     *              @OA\Property(type="string", property="password"),
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
