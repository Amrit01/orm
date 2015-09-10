<?php

namespace LaravelDoctrine\ORM\Configuration\MetaData;

use Doctrine\ORM\Tools\Setup;
use Illuminate\Contracts\Config\Repository;
use LaravelDoctrine\ORM\Configuration\Cache\CacheManager;
use LaravelDoctrine\ORM\Configuration\Driver;
use LaravelDoctrine\ORM\Configuration\MetaData\Config\ConfigDriver;

class Config implements Driver
{
    /**
     * @var CacheManager
     */
    protected $cacheManager;

    /**
     * @var Repository
     */
    protected $config;

    /**
     * @param CacheManager $cacheManager
     * @param Repository   $config
     */
    public function __construct(CacheManager $cacheManager, Repository $config)
    {
        $this->cacheManager = $cacheManager;
        $this->config       = $config;
    }

    /**
     * @param array $settings
     *
     * @return \Doctrine\ORM\Configuration|mixed
     */
    public function resolve(array $settings = [])
    {
        $configuration = Setup::createConfiguration(
            array_get($settings, 'dev'),
            array_get($settings, 'proxies.path'),
            $this->cacheManager->driver()
        );

        $configuration->setMetadataDriverImpl(
            new ConfigDriver(
                $this->config->get(array_get($settings, 'mapping_file'), [])
            )
        );

        return $configuration;
    }
}
