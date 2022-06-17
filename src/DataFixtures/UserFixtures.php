<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\CompanyScore;
use App\Entity\Rating;
use App\Entity\Review;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @codeCoverageIgnore
 */
class UserFixtures extends Fixture
{
    private const FILE = 'data.json';

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $userObj = new User();
        $userObj->setEmail('user1');
        $userObj->setRoles([USER::ROLES['ROLE_REVIEWER']]);
        $userObj->setApiToken('user1token');
        $userObj->setPassword(
            $this->passwordHasher->hashPassword(
                $userObj,
                'user1'
            )
        );

        $manager->persist($userObj);

        $this->loadJson($manager);
        $manager->flush();
    }

    private function loadJson(ObjectManager $manager)
    {
        $string = file_get_contents(self::FILE, true);

        $json = json_decode($string, true);

        $userStrings = [];
        $users = [];

        //get usernames
        foreach ($json['companies'] as $company) {
            foreach ($company['reviews'] as $review) {
                $userStrings[$review['user']] = $review['user'];
            }
        }

        //create users from usernames
        foreach ($userStrings as $user) {
            $userObj = new User();
            $userObj->setEmail($user);
            $userObj->setRoles([USER::ROLES['ROLE_REVIEWER']]);
            $userObj->setApiToken($user);
            $userObj->setPassword(
                $this->passwordHasher->hashPassword(
                    $userObj,
                    'user1'
                )
            );

            $users[$user] = $userObj;
            $manager->persist($userObj);
        }


        //create companies
        foreach ($json['companies'] as $company) {
            $score = 0;
            $companyObj = new Company(
                $company['name'] ?? 'name',
                $company['slug'] ?? 'slug',
                $company['city'] ?? 'city',
                $company['country'] ?? 'country',
                $company['country'] ?? 'country',
            );


            //create reviews
            foreach ($company['reviews'] as $review) {
                $reviewObj = new Review(
                    $review['title'] ?? 'title',
                    $review['pro'] ?? '',
                    $review['contra'] ?? '',
                    $review['suggestions'] ?? '',
                    new Rating(
                        $review['rating']['culture'] ?? 0,
                        $review['rating']['management'] ?? 0,
                        $review['rating']['work_live_balance'] ?? 0,
                        $review['rating']['career_development'] ?? 0,
                    ),
                    $companyObj,
                    $users[$review['user']]
                );
                $score += $reviewObj->getRating()->getScore();
                $manager->persist($reviewObj);
            }

            $final = round(($score / count($company['reviews']) / Rating::METRICS_COUNT), 2) * 100 ?? 0;
            $companyObj->getScore()->updateScore($final);
            $manager->persist($companyObj);
        }
    }
}
