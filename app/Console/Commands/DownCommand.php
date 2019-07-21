<?php

namespace Arc\Console\Commands;

use Arc\Events\QOL\Offline;
use Exception;
use Illuminate\Foundation\Console\DownCommand as BaseDownCommand;

class DownCommand extends BaseDownCommand
{
    public function handle()
    {
        broadcast(new Offline);
        parent::handle();
    }
}