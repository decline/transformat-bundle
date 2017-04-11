<?php

namespace Decline\TransformatBundle\Tests;

use PHPUnit\Framework\BaseTestListener;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class CleanupListener
 * @package Decline\TransformatBundle\Tests
 */
class CleanupListener extends BaseTestListener
{
    /**
     * Removes the cache- and logs-directories after the test suite finishes
     * @param \PHPUnit_Framework_TestSuite $suite
     */
    public function endTestSuite(\PHPUnit_Framework_TestSuite $suite)
    {
        $fs = new Filesystem();
        $fs->remove(
            [
                __DIR__ . '/App/cache',
                __DIR__ . '/App/logs',
            ]
        );
    }

}