<?php

use Arc\Models\Channel;
use Illuminate\Database\Seeder;

class ChannelSeeder extends Seeder
{
    private $data = [
        [
            'name'        => 'general',
            'description' => 'This is the general channel. It always has existed, and always will exist.',
            'default'     => true,
        ],
        [
            'name'        => 'random',
            'description' => 'This is where people post weird ass shit.',
            'default'     => true,
        ],
        [
            'name'        => 'larahack',
            'description' => 'This is where people discuss the Laravel hackathon, larahack.',
            'default'     => true,
        ],
        [
            'name'        => 'secret',
            'description' => 'This channel doesn\'t exist, you can\'t prove anything!',
            'private'     => true,
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->data as $row) {
            $channel = (new Channel)->fill($row);

            if ($channel->save()) {
                $this->command->info(sprintf('Channel #%s created', $channel->name));
            }
        }
    }
}
