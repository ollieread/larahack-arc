<?php

namespace Arc\Console\Commands;

use Arc\Events\QOL\Online;
use Illuminate\Foundation\Console\UpCommand as BaseUpCommand;

class UpCommand extends BaseUpCommand
{
    public function handle()
    {
        broadcast(new Online);
        parent::handle();
    }
}