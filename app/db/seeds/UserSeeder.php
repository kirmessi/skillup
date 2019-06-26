<?php


use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = [
            [
                'login' => 'sergei',
                'email' => 'sergei@test.ru',
                'password' => 'fGhvs4Fgj',
            ],
            [
                'login' => 'ivan',
                'email' => 'ivan@test.ru',
                'password' => 'gHdc57jFsaw',
            ],
            [
                'login' => 'ivan',
                'email' => 'ivan@test.ru',
                'password' => 'jlS56nH',
            ]
        ];

        $user = $this->table('wp_test');
        $user->insert($data)->save();
    }
}
