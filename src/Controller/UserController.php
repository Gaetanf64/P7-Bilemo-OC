<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Entity\Client;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class UserController extends AbstractController
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
     * Liste des users
     * 
     * @Rest\Get(
     *     path = "/api/users",
     *     name = "api_users_liste",
     * ),
     * @Rest\View(statusCode= 200),
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
    public function list(UserRepository $userRepository, Request $request, PaginatorInterface $paginator, TagAwareCacheInterface $cache): Response
    {
        $page = $request->query->getInt('page', 1);

        //Use cache
        $usersCache = $cache->get("users" . $page, function (ItemInterface $item) use ($page, $paginator, $userRepository) {
            $item->expiresAfter(3600);
            $item->tag('user');

            //On recupère l'ID du client
            $client = $this->getUser();
            $idClient = $client->getId();

            $users = $userRepository->findByClient($idClient);

            //Mise en place de la pagination
            $usersPaginator = $paginator->paginate($users, $page, 2);

            $json = $this->serializer->serialize($usersPaginator, 'json');
            $response = new Response($json, 200, [], true);

            return $response;
        });

        return $usersCache;
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
    public function show($id, UserRepository $userRepository, TagAwareCacheInterface $cache): Response
    {

        //Use cache
        $userCache = $cache->get("user" . $id, function (ItemInterface $item) use ($id, $userRepository) {
            $item->expiresAfter(3600);

            //On recupère l'ID du client
            $idClient = $this->getUser()->getId();

            $user = $userRepository->findOneById($id);

            //si user n'existe pas
            if ($user == null) {
                throw new HttpException(404, "Not found");
            } else {
                $userClient = $user->getClient()->getId();
                //si le user appartient au bon client
                if ($userClient === $idClient) {
                    $json = $this->serializer->serialize($user, 'json');
                    $response = new Response($json, 200, [], true);

                    return $response;
                } else {
                    throw new HttpException(403, "You cannot access this user from another client");
                }
            }
        });

        return $userCache;
    }

    /**
     * Créer un user
     * 
     * @Rest\Post(path="/api/users", name="api_add_user")
     * @Rest\View(statusCode= 201)
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
    public function add(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager): Response
    {
        //On récupère le contenu 
        $json = $request->getContent();

        $user = $this->serializer->deserialize($json, User::class, 'json');

        //On encode le mot de passe
        $password = $encoder->encodePassword($user, $user->getPassword());

        $user->setPassword($password)
            ->setRoles(["ROLE_USER"])
            ->setDateCreation(new \DateTime(date('Y-m-d H:i:s')))
            ->setDateUpdate(new \DateTime(date('Y-m-d H:i:s')))
            ->setClient($this->getUser());

        //On hydrate
        $manager->persist($user);

        //Envoi dans la base de données
        $manager->flush();

        $json = $this->serializer->serialize($user, 'json');
        $response = new Response($json, 201, [], true);
        return $response;
    }

    /**
     * Effacer un user
     * 
     * @Rest\Delete(path="/api/users/{id}", name="api_delete_user")
     * @Rest\View(statusCode= 204)
     * @OA\Delete(
     *     path="/users/{id}",
     *     tags={"Users"},
     *     security={"bearer"},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID du user",
     *          required=true,
     *          @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *          response="204",
     *          description="Delete User",
     *     ),
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
    public function delete($id, UserRepository $userRepository, EntityManagerInterface $manager)
    {
        //On récupère l'ID du client
        $idClient = $this->getUser()->getId();

        $user = $userRepository->find($id);

        //Si user n'existe pas
        if ($user == null) {
            throw new HttpException(404, "Not found");
        } else {
            $userClient = $user->getClient()->getId();

            //si user appartient au bon client
            if ($userClient === $idClient) {
                //On supprime le user
                $manager->remove($user);
                $manager->flush();

                return new Response("User deleted");
            } else {
                throw new HttpException(403, "You cannot access this user from another client");
            }
        }
    }
}
