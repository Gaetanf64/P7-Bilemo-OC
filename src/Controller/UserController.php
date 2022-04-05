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
    public function list(UserRepository $userRepository): Response
    {
        $client = $this->getUser();
        $idClient = $client->getId();

        $users = $userRepository->findByClient($idClient);

        $json = $this->serializer->serialize($users, 'json');
        $response = new Response($json, 200, [], true);

        return $response;
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
    public function show($id, UserRepository $userRepository): Response
    {
        $idClient = $this->getUser()->getId();

        $user = $userRepository->find($id);
        $userClient = $user->getClient()->getId();

        if ($userClient === $idClient) {
            $json = $this->serializer->serialize($user, 'json');
            $response = new Response($json, 200, [], true);

            return $response;
        } else {
            throw new HttpException(403, "You cannot access this user from another client");
        }
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
        $json = $request->getContent();

        $user = $this->serializer->deserialize($json, User::class, 'json');

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
        $idClient = $this->getUser()->getId();

        $user = $userRepository->find($id);
        $userClient = $user->getClient()->getId();

        if ($userClient === $idClient) {
            $manager->remove($user);
            $manager->flush();

            return new Response("User deleted");
        } else {
            throw new HttpException(403, "You cannot access this user from another client");
        }
    }
}
