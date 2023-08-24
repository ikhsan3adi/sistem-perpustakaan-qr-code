<?php

namespace Tests\Support\Models;

use App\Models\MemberModel;
use Faker\Generator;
use Faker\Provider\Person;

class MemberFabricator extends MemberModel
{
    public function fake(Generator &$faker)
    {
        // 1: Male, 2: Female
        $gender = $faker->numberBetween(1, 2);

        $firstName = $faker->firstName($gender == 1 ? Person::GENDER_MALE : Person::GENDER_FEMALE);
        $lastName = $faker->lastName($gender == 1 ? Person::GENDER_MALE : Person::GENDER_FEMALE);

        return [
            'uid'           => sha1($firstName . $lastName . rand(0, 100)),
            'first_name'    => $firstName,
            'last_name'     => $lastName,
            'email'         => $faker->email,
            'phone'         => $faker->phoneNumber,
            'address'       => $faker->address,
            'date_of_birth' => $faker->date,
            'gender'        => $gender,
        ];
    }
}
