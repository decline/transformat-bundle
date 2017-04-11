<?php

namespace Decline\TransformatBundle\Tests\App;

use Decline\TransformatBundle\DeclineTransformatBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Class AppKernel
 * @package Decline\TransformatBundle\Tests\App
 */
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array();

        if ('test' === $this->getEnvironment()) {
            $bundles[] = new FrameworkBundle();
            $bundles[] = new TwigBundle();
            $bundles[] = new DeclineTransformatBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/config.yml');
    }
}