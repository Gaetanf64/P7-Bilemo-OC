<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations as Rest;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

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
    public function list(ProductRepository $productRepository, Request $request, PaginatorInterface $paginator, TagAwareCacheInterface $cache): Response
    {
        $page = $request->query->getInt("page", 1);

        //Use cache
        $productsCache = $cache->get("products" . $page, function (ItemInterface $item) use ($page, $paginator, $productRepository) {
            $item->expiresAfter(3600);
            $item->tag('product');


            $products = $productRepository->findAll();

            //Mise en place de la pagination
            $productsPaginator = $paginator->paginate($products, $page, 6);

            $json = $this->serializer->serialize($productsPaginator, 'json');
            $response = new Response($json, 200, [], true);

            return $response;
        });

        return $productsCache;
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
    public function show($id, ProductRepository $productRepository, TagAwareCacheInterface $cache): Response
    {
        //Use cache
        $productCache = $cache->get("product" . $id, function (ItemInterface $item) use ($id, $productRepository) {
            $item->expiresAfter(3600);

            $product = $productRepository->findOneById($id);

            //Si produit n'existe pas
            if ($product == null) {
                throw new HttpException(404, "Not found");
            } else {

                $json = $this->serializer->serialize($product, 'json');
                $response = new Response($json, 200, [], true);

                return $response;
            }
        });

        return $productCache;
    }
}
