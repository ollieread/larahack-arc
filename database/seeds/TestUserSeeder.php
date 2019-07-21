<?php

use Arc\Operations\Users\RegisterUser;
use Faker\Factory;
use Illuminate\Database\Seeder;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            try {
                $input = [
                    'username'              => $faker->userName,
                    'email'                 => $faker->email,
                    'password'              => 'password32',
                    'password_confirmation' => 'password32',
                ];
                (new RegisterUser)->setInput($input)->perform($input);
            } catch (\Illuminate\Validation\ValidationException $exception) {
                dd($exception->validator->failed(), $input);
            }
        }
    }
}
