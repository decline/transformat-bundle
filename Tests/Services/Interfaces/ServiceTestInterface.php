<?php

namespace Decline\TransformatBundle\Tests\Services\Interfaces;

/**
 * Interface ServiceTestInterface
 * @package Decline\TransformatBundle\Tests\Services
 */
interface ServiceTestInterface
{
    const RESOURCES_DIR = __DIR__ . '/../../App/Resources';

    const TRANSLATION_UNFORMATTED = 'translations-unformatted/unformatted.de.xlf';
    const TRANSLATION_FORMATTED = 'translations-unformatted/formatted.de.xlf';
    const TRANSLATION_EXPECTED = 'translations-unformatted/expected.de.xlf';

    const TRANSLATION_VALID = 'translations/foobar.de.xlf';
    const TRANSLATION_INVALID = 'translations-faulty/no-trans-units.de.xlf';
}