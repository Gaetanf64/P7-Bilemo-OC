<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use JMS\Serializer\SerializerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;

class ProductController extends AbstractController
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function  __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Liste des produits
     * 
     * @Rest\Get(
     *     path = "/api/products",
     *     name = "api_products_liste",
     * ),
     * @Rest\View(statusCode= 200),
     * @OA\Get(
     *      path="/products",
     *      tags={"Products"},
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
     *          description="Liste des produits",
     *          @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Product")),
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
    public function liste(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        $json = $this->serializer->serialize($products, 'json');
        $response = new Response($json, 200, [], true);

        return $response;
    }

    /**
     * Détail d'un produit
     * 
     * @Rest\Get(path="/api/products/{id}", name="api_product_details")
     * @Rest\View(statusCode= 200)
     * @OA\Get(
     *      path="/products/{id}",
     *      security={"bearer"},
     *      tags={"Products"},
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
}
