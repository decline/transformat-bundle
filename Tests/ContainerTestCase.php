<?php

namespace Decline\TransformatBundle\Tests;

use Decline\TransformatBundle\Tests\App\AppKernel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ContainerTestCase
 * @package Decline\TransformatBundle\Tests
 */
abstract class ContainerTestCase extends KernelTestCase
{

    /**
     * The class of the Kernel to boot for this test
     * @var string
     */
    protected static $class = AppKernel::class;

    /**
     * The Container
     * @var ContainerInterface
     */
    protected static $container;

    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass()
    {
        self::bootKernel();
        self::$container = self::$kernel->getContainer();
    }
}