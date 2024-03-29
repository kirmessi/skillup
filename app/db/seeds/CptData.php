<?php


use Phinx\Seed\AbstractSeed;

/**
 * Class CptData
 */
class CptData extends AbstractSeed
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
        $faker = Faker\Factory::create();
        $data = [];
        for ($i = 0; $i < 100; $i++) {
            $data[] = [
                'title'      => $faker->name,
                'description'      => sha1($faker->text),
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ];
        }
        $user = $this->table('wp_cpt_test');
        $user->insert($data)->save();
    }
}
