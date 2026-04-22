<?php

use Illuminate\Foundation\Application;
use Illuminate\Config\Repository as ConfigRepository;

$app = new Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

/*
|--------------------------------------------------------------------------
| Register The Configuration
|--------------------------------------------------------------------------
|
| The application needs a configuration repository to function. 
| We'll create and register it here.
|
*/

$config = new ConfigRepository();

// Load all configuration files found in the config directory.
$configPath = $app->basePath('config');
if (is_dir($configPath)) {
    foreach (glob($configPath . DIRECTORY_SEPARATOR . '*.php') as $file) {
        $config->set(pathinfo($file, PATHINFO_FILENAME), require $file);
    }
}

$app->instance('config', $config);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

return $app;
