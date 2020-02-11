<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;
    function __construct(UserPasswordEncoderInterface $passwordEncoder){
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {
         // user avec un rôle d'admin
       $user = new User();
       $pass = $this->passwordEncoder->encodePassword($user, 'secret1');
       $user
            ->setEmail('admin@airways.fr')
            ->setPassword(($pass))
            ->setRoles(['ROLE_ADMIN']);
            $manager->persist($user);


        // user avec un rôle d'utilisateur simple
        $user = new User();
        $pass = $this->passwordEncoder->encodePassword($user, 'secret2');
        $user
                ->setEmail('louis@airways.fr')
                ->setPassword(($pass));
                $manager->persist($user);

        $manager->flush();
    }
    
}
