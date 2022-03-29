<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class ProductController extends AbstractController
{
    /**
     * Liste des produits
     * 
     * @OA\Get(
     *      path="/products",
     *      @OA\Parameter(
     *          name="liste",
     *          in="query",
     *          description="Le nombre de produits à récupérer",
     *          required=false,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Liste des produits",
     *          @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Product")),
     *      )
     * )
     */
    public function list(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ProductController.php',
        ]);
    }

    /**
     * Détail d'un produit
     * 
     * @OA\Get(
     *      path="/products/{id}",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID du produit",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Détail d'un produit",
     *          @OA\JsonContent(ref="#/components/schemas/Product"),
     *      )
     * )
     */
    public function details(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ProductController.php',
        ]);
    }
}
