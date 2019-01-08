<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('Fr-fr');

        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser->setFirstName('François')
                  ->setLastName('Jacquin')
                  ->setEmail('jacquin.fran@gmail.com')
                  ->setHash($this->encoder->encodePassword($adminUser, 'password'))
                  ->setPicture('https://randomuser.me/api/portraits/men/91.jpg')
                  ->setIntroduction($faker->sentence())
                  ->setDescription('<p>'.join('</p><p>', $faker->paragraphs(3)).'</p>')
                  ->addUserRole($adminRole);
        $manager->persist($adminUser);

        //Nous gérons les utilisateurs
        $users = [];
        $genres = ['male', 'female']; 
        for($i = 1; $i <= 10; $i++){
            $user = new User();

            $genre = $faker->randomElement($genres);

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1,99) . '.jpg';

           $picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;

            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstname($faker->firstname($genre))
                 ->setLastname($faker->lastname)
                 ->setEmail($faker->email)
                 ->setIntroduction($faker->sentence())
                 ->setDescription('<p>'.join('</p><p>', $faker->paragraphs(3)).'</p>')
                 ->setHash($hash)
                 ->setPicture($picture);

            $manager->persist($user);
            $users[] = $user;
        }
        
        //Nous gérons les annonces
        for($i = 1; $i <= 30; $i++){
            $ad = new Ad;

            $title = $faker->sentence(5);
            $coverImage = $faker->imageUrl(1000,400);
            $introduction = $faker->paragraph(2);
            $content = '<p>'.join('</p><p>', $faker->paragraphs(5)).'</p>';

            $user = $users[mt_rand(0 , count($users) -1 )];
            
            $ad->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40,200))
                ->setRooms(mt_rand(1,5))
                ->setAuthor($user);
                //ajout des images pour une annonce
                for($j = 1; $j <mt_rand(2,5); $j++){
                    $image = new Image;
                    $image->setUrl($faker->imageUrl())
                          ->setCaption($faker->sentence())
                          ->setAd($ad);
                        
                    $manager->persist($image);
                }
        
              $manager->persist($ad);
        }
        $manager->flush();
    }
}
