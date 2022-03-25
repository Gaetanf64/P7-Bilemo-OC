<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Entity\Client;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ClientFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $clients = [
            1 => [
                'email' => 'contact@sfr.fr',
                'username' => 'SFR',
                'password' => 'sfr',
                'roles' => 'ROLE_ADMIN',
            ],
            2 => [
                'email' => 'contact@orange.fr',
                'username' => 'Orange',
                'password' => 'orange',
                'roles' => 'ROLE_ADMIN',
            ],
            3 => [
                'email' => 'contact@bouygues.fr',
                'username' => 'Bouygues',
                'password' => 'bouygues',
                'roles' => 'ROLE_ADMIN',
            ],
            4 => [
                'email' => 'contact@free.fr',
                'username' => 'Free',
                'password' => 'free',
                'roles' => 'ROLE_ADMIN',
            ],
        ];

        foreach ($clients as $key => $value) {
            $client = new Client();

            $client->setEmail($value['email']);
            $client->setUsername($value['username']);
            $client->setRoles(array($value['roles']));
            $client->setDateCreation(new \DateTime(date('Y-m-d H:i:s')));
            $client->setDateUpdate(new \DateTime(date('Y-m-d H:i:s')));

            $password = $this->encoder->encodePassword($client, $value['password']);
            $client->setPassword($password);

            //Add Reference
            $this->addReference('client_' . $key, $client);

            $manager->persist($client);
        }

        $manager->flush();
    }
}
