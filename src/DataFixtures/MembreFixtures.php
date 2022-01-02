<?php

namespace App\DataFixtures;

use App\Entity\Membre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class MembreFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {

    }

    public function load(ObjectManager $manager)
    {

        $faker = \Faker\Factory::create('fr_FR');

        //membre admin
        $membre = (new Membre())
            ->setNumLicence($faker->unique->randomNumber(6, true).strtoupper($faker->randomLetter()))
            ->setNom($faker->lastName())
            ->setPrenom($faker->firstName('male'))
            ->setDateNaissance($faker->dateTimeBetween('-80 years', '-18 years'))
            ->setTelMobile($faker->phoneNumber())
            ->setEmail('thomas.huaume@hotmail.fr')
            ->setLateralite($faker->randomElement(['D', 'G']))
            ->setStatutLicence(1)
            //Password = "aaaaaa"
            ->setPassword('$2y$13$zAfnuQj96kOkDkmWLqO6suXF.GkYR2TuzbR3ZLKa5ij4kMddOQYVC')
            ->setSexe('Homme')
            ->setCategorieAge($faker->randomElement([
                'Sénior 3',
                'Sénior 2',
                'Sénior 1',
                'Cadet',
                'Benjamin',
                'Minime',
                'Junior',
                'Poussin',
                'Poussin'
            ]))
            ->setTypeLicence($faker->randomElement([
                'ADULTE Pratique en compétition',
                'Jeune',
                'Poussin',
                'Jeune'
            ]))
            ->setRoles(array('ROLE_ADMIN'));
        $manager->persist($membre);

        //membres secrétaires
        for($i = 0; $i < 2; $i++){
            $membre = (new Membre())
                ->setNumLicence($faker->unique->randomNumber(6, true).strtoupper($faker->randomLetter()))
                ->setNom($faker->lastName())
                ->setPrenom($faker->firstName('male'))
                ->setDateNaissance($faker->dateTimeBetween('-80 years', '-18 years'))
                ->setTelMobile($faker->phoneNumber())
                ->setEmail($faker->email())
                ->setLateralite($faker->randomElement(['D', 'G']))
                ->setStatutLicence(1)
                //Password = "aaaaaa"
                ->setPassword('$2y$13$zAfnuQj96kOkDkmWLqO6suXF.GkYR2TuzbR3ZLKa5ij4kMddOQYVC')
                ->setSexe('Homme')
                ->setCategorieAge($faker->randomElement([
                    'Sénior 3',
                    'Sénior 2',
                    'Sénior 1',
                    'Cadet',
                    'Benjamin',
                    'Minime',
                    'Junior',
                    'Poussin',
                    'Poussin'
                ]))
                ->setTypeLicence($faker->randomElement([
                    'ADULTE Pratique en compétition',
                    'Jeune',
                    'Poussin',
                    'Jeune'
                ]))
                ->setRoles(array('ROLE_SECRETAIRE'));
            $manager->persist($membre);
        }

        //membres user
        for($i = 1; $i <= 100; $i++){   //!!!Modifier dans Inscription/Reservation
            $membre = (new Membre())
                ->setNumLicence($faker->unique->randomNumber(6, true).strtoupper($faker->randomLetter()))
                ->setNom($faker->lastName())
                ->setPrenom($faker->firstName())
                ->setDateNaissance($faker->dateTimeBetween('-80 years', '-15 years'))
                ->setTelMobile($faker->phoneNumber())
                ->setEmail($faker->email())
                ->setLateralite($faker->randomElement(['D', 'G']))
                ->setStatutLicence(1)
                //Password = "aaaaaa"
                ->setPassword('$2y$13$zAfnuQj96kOkDkmWLqO6suXF.GkYR2TuzbR3ZLKa5ij4kMddOQYVC')
                ->setSexe($faker->randomElement([
                    'Homme',
                    'Femme'
                ]))
                ->setCategorieAge($faker->randomElement([
                    'Sénior 3',
                    'Sénior 2',
                    'Sénior 1',
                    'Cadet',
                    'Benjamin',
                    'Minime',
                    'Junior',
                    'Poussin',
                    'Poussin'
                ]))
                ->setTypeLicence($faker->randomElement([
                    'ADULTE Pratique en compétition',
                    'Jeune',
                    'Poussin',
                    'Jeune'
                ]))
                ->setRoles(array('ROLE_USER'));
            $this->addReference(Membre::class.'_'.$i, $membre);
            $manager->persist($membre);
        }

        $manager->flush();
    }
}