<?php

namespace Decline\TransformatBundle\Tests\Services;

use Decline\TransformatBundle\Services\ValidatorService;
use Decline\TransformatBundle\Tests\ContainerTestCase;
use Decline\TransformatBundle\Tests\Services\Interfaces\ServiceTestInterface;

/**
 * Class ValidatorServiceTest
 * @package Decline\TransformatBundle\Tests\Services
 */
class ValidatorServiceTest extends ContainerTestCase implements ServiceTestInterface
{

    /**
     * Tests the validation of the translation files
     */
    public function testValidate()
    {
        $validXliff = $this->getValidXliff();
        $invalidXliff = $this->getInvalidXliff();

        $validator = $this->getValidator(ValidatorService::MODE_DISABLED);
        $strictValidator = $this->getValidator(ValidatorService::MODE_STRICT);
        $transitionalValidator = $this->getValidator(ValidatorService::MODE_TRANSITIONAL);

        $this->assertCount(0, $validator->validate($validXliff));
        $this->assertCount(0, $validator->validate($invalidXliff));

        $this->assertCount(0, $strictValidator->validate($validXliff));
        $this->assertCount(1, $strictValidator->validate($invalidXliff));

        $this->assertCount(0, $transitionalValidator->validate($validXliff));
        $this->assertCount(1, $transitionalValidator->validate($invalidXliff));
    }

    /**
     * Gets the validator for the given validation mode
     *
     * @param string $mode
     *
     * @return ValidatorService
     */
    private function getValidator($mode)
    {
        return new ValidatorService(['xliff' => ['validation' => $mode]]);
    }

    /**
     * Returns the content of a valid xliff file
     * @return string
     */
    private function getValidXliff()
    {
        return (string) file_get_contents(static::RESOURCES_DIR . '/' . static::TRANSLATION_VALID);
    }

    /**
     * Returns the content of an invalid xliff file
     * @return string
     */
    private function getInvalidXliff()
    {
        return (string) file_get_contents(static::RESOURCES_DIR . '/' . static::TRANSLATION_INVALID);
    }
}