<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $users = [
            1 => [
                'email' => 'gaetan.fouillet@gmail.com',
                'username' => 'Gaetan',
                'password' => 'gaetan',
                'roles' => 'ROLE_USER',
                'client' => 1,
            ],
            2 => [
                'email' => 'leo@gmail.com',
                'username' => 'Leo',
                'password' => 'leo',
                'roles' => 'ROLE_USER',
                'client' => 1,
            ],
            3 => [
                'email' => 'thierry@gmail.com',
                'username' => 'Thierry',
                'password' => 'thierry',
                'roles' => 'ROLE_USER',
                'client' => 2,
            ],
            4 => [
                'email' => 'valerie@gmail.com',
                'username' => 'Valerie',
                'password' => 'valerie',
                'roles' => 'ROLE_USER',
                'client' => 2,
            ],
            5 => [
                'email' => 'frederic@gmail.com',
                'username' => 'Frederic',
                'password' => 'frederic',
                'roles' => 'ROLE_USER',
                'client' => 3,
            ],
            6 => [
                'email' => 'laurent@gmail.com',
                'username' => 'Laurent',
                'password' => 'laurent',
                'roles' => 'ROLE_USER',
                'client' => 1,
            ],
            7 => [
                'email' => 'sophie@gmail.com',
                'username' => 'Sophie',
                'password' => 'sophie',
                'roles' => 'ROLE_USER',
                'client' => 4,
            ],
            8 => [
                'email' => 'emma@gmail.com',
                'username' => 'Emma',
                'password' => 'emma',
                'roles' => 'ROLE_USER',
                'client' => 4,
            ],
        ];

        foreach ($users as $key => $value) {
            $user = new User();

            $user->setEmail($value['email']);
            $user->setUsername($value['username']);
            $user->setRoles(array($value['roles']));
            $user->setDateCreation(new \DateTime(date('Y-m-d H:i:s')));
            $user->setDateUpdate(new \DateTime(date('Y-m-d H:i:s')));

            $password = $this->encoder->encodePassword($user, $value['password']);
            $user->setPassword($password);

            //Lien avec d'autres fixtures
            $user->setClient($this->getReference('client_' . $value['client']));

            $manager->persist($user);
        }

        $manager->flush();
    }
}
