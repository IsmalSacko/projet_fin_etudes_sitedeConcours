<?php

namespace App\DataFixtures;

use App\Entity\Annonces;
use App\Entity\Departement;
use App\Entity\Regions;
use App\Entity\Role;
use App\Entity\TypeConcours;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
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
        $faker = Factory::create('fr_FR');
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
        //$manager->flush();
            $users =[];
        for ($i=0; $i<= 20; $i++){
            $user2 = new User();
            $content = '<p>'.join('<p></p>', $faker->paragraphs(4));'</p>';
            $user2->setFirstName($faker->firstName())
                ->setLastName($faker->firstName())
                ->setEmail($faker->email())
                ->setHash($this->encoder->encodePassword($user, "sackosacko"))
                ->setIntro($faker->paragraph(2))
                ->setPresentation($content)
                ->setPicture($faker->imageUrl(400, 400));
            $manager->persist($user2);
            $users[]= $user2;
        }
        $concurs = [
            'doublette formée',
            'triplette formée',
            'tête à tête',
            'doublette à la mélée',
            'triplette à la mélée',
            'triplette mixte formée',
            'doublette féminine formée',
            'triplette féminine formée',
        ];
        $concs =[];
       for($c=0; $c<=8; $c++){
           $typeconcours = new TypeConcours();
           $typeconcours->setName('Doulette à la mélée');
           $manager->persist($typeconcours);
           $concs []=  $typeconcours;
       }
        $departs =[];
        for($d=0; $d<=10; $d++){
            $code = $faker->postcode;

            $departement = new Departement();
            $departement->setName($faker->city)
                        ->setCode(intval($code))
                        ->setDepartementNomUppercase($faker->city)
                        ->setDepartementSlug($faker->city)
                        ->setDepartementNomSoundex($faker->city);
            $manager->persist($departement);
            $departs []=  $departement;
        }
        for($r=0; $r<=10; $r++){
            //$code = $faker->postcode;

            $region = new Regions();
            $region->setCode($faker->postcode)
                    ->setName($faker->city())
                ->setSlug($faker->city());
            $manager->persist($region);
            $regions []=  $region;
        }

        for ($j=0; $j<=20; $j++){
            $ad = new Annonces();
            $content = '<p>'.join('<p></p>', $faker->paragraphs(4));'</p>';


            $user = $users[mt_rand(0, count($users) - 1)];
            $conc = $concs[mt_rand(0, count($concs) - 1)];
            $depart = $departs[mt_rand(0, count($departs) - 1)];
            $regs = $regions[mt_rand(0, count($regions) - 1)];
            $ad->setTitle($faker->sentence())
                ->setTypeConcours($conc)
                ->setContent($content)
                ->setAuthor($user)
                ->setImage($faker->imageUrl(500, 500))
                ->setRegion($regs)
                ->setDepartement($depart)
                ->setCreatedAt($faker->dateTime("now"));
            $manager->persist($ad);

        }

        $manager->flush();


    }


}
