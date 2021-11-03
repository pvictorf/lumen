<?php

namespace App\Providers;

use Pion\Laravel\ChunkUpload\Config\AbstractConfig;
use Pion\Laravel\ChunkUpload\Config\FileConfig;

class ChunkUploadServiceProvider extends \Pion\Laravel\ChunkUpload\Providers\ChunkUploadServiceProvider
{
    public function boot()
    {
        $config = $this->app->make(AbstractConfig::class);

        // I didn't need the scheduler or other features they provide so I was able to go with this, as the booted methods don't exist in lumen

        $this->registerHandlers($config->handlers());
    }

    protected function registerConfig()
    {
        // Config options
        $configIndex = FileConfig::FILE_NAME;
        $configFileName = FileConfig::FILE_NAME.'.php';
        $configPath = __DIR__.'/../../config/'.$configFileName;

        // Publish the config
        $this->publishes([
            $configPath => config_path($configFileName),
        ]);

        // Merge the default config to prevent any crash or unfilled configs
        $this->mergeConfigFrom(
            $configPath,
            $configIndex
        );

        return $this;
    }
}