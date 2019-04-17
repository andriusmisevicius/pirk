<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i <10; $i++){
            $user = new User();
            $user->setEmail('testuser'.$i.'@users.com');
            $user->setName('Test User'.$i);
            $user->setPhone('3706542526'.$i);
            $password = $this->encoder->encodePassword($user, 'testuser');
            $user->setPassword($password);

            $manager->persist($user);
        }

        $user = new User();
        $user->setEmail('admin@admin.com');
        $user->setName('Page Admin'.$i);
        $user->setPhone('37065425255');
        $user->setRoles(['ROLE_ADMIN','ROLE_USER']);
        $password = $this->encoder->encodePassword($user, 'adminadmin');
        $user->setPassword($password);
        $manager->persist($user);

        $manager->flush();
    }
}
