<?php

namespace Arc\Http\Actions;

use Arc\Support\Action;

class SPA extends Action
{
    public function __invoke()
    {
        return $this->response()->view('spa');
    }
}