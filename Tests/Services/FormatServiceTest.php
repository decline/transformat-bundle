<?php

namespace Decline\TransformatBundle\Tests\Services;

use Decline\TransformatBundle\Tests\ContainerTestCase;

/**
 * Class FormatServiceTest
 * @package Decline\TransformatBundle\Tests\Services
 */
class FormatServiceTest extends ContainerTestCase
{

    const RESOURCES_DIR = __DIR__ . '/../App/Resources';

    const TRANSLATION_UNFORMATTED = 'translations-unformatted/unformatted.de.xlf';
    const TRANSLATION_FORMATTED = 'translations-unformatted/formatted.de.xlf';
    const TRANSLATION_EXPECTED = 'translations-unformatted/expected.de.xlf';

    /**
     * Tests the actual formatting / ordering of the files content
     */
    public function testFormat()
    {
        $fileSystem = static::$container->get('filesystem');
        $formatService = static::$container->get('decline_transformat.format');

        $fileUnformatted = static::RESOURCES_DIR . '/' . static::TRANSLATION_UNFORMATTED;
        $fileFormatted = static::RESOURCES_DIR . '/' . static::TRANSLATION_FORMATTED;
        $fileExpected = static::RESOURCES_DIR . '/' . static::TRANSLATION_EXPECTED;

        // create a copy of the unformatted file
        $fileSystem->copy($fileUnformatted, $fileFormatted);

        // format the unformatted file
        $formatService->format(null, '../' . static::TRANSLATION_FORMATTED);

        // formatted file should be equal to expected file
        $this->assertFileEquals($fileExpected, $fileFormatted);

        // cleanup by removing formatted file
        $fileSystem->remove([$fileFormatted]);
    }
}