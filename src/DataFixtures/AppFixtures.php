<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    /**
     * AppFixtures constructor.
     * @param $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $adminRole = new Role();
        $adminRole->setTitle("ROLE_ADMIN");
        $manager->persist($adminRole);
        $user = new User();
        $user->setFirstName("SACKO")
            ->setLastName("Ismaél")
            ->setEmail("ismalsacko@yahoo.fr")
            ->setHash($this->encoder->encodePassword($user, "sackosacko"))
            ->setIntro("Je suis administrateur de ce site")
            ->setPresentation("Etudiant passionné du metier du web et ce site est le fruit de cette passion")
            ->setPicture("Scan.jpg")
            ->addUsersRole($adminRole);
        $manager->persist($user);
        $manager->flush();

        $user_2 = new User();
        $user_2->setFirstName("NIAFO")
            ->setLastName("AICHA")
            ->setEmail("aicha@yahoo.fr")
            ->setHash($this->encoder->encodePassword($user_2, "sackosacko"))
            ->setIntro("Je suis utilisatrice de ce site")
            ->setPresentation("Etudiante et passionnée de la medecine moderne et profite de ce site pour proposer mes services à mes clients")
            ->setPicture("935ba65f2604f61fb4f50b6700e6830.jpeg");

        $manager->persist($user_2);
        $manager->flush();
    }
}
