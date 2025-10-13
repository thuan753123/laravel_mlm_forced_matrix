<?php

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

return function ($app) {
    $app->singleton(Illuminate\Contracts\Console\Kernel::class, function ($app) {
        return new ConsoleKernel($app);
    });
};
