<?php

namespace Decline\TransformatBundle\Services;

use DOMDocument;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

/**
 * Class ValidatorService
 * @package Decline\TransformatBundle\Services
 */
class ValidatorService
{

    const MODE_STRICT = 'strict';
    const MODE_TRANSITIONAL = 'transitional';
    const MODE_DISABLED = 'false';

    /**
     * @var array
     */
    private $config;

    /**
     * @var string
     */
    private $schemaContent;

    /**
     * ValidatorService constructor.
     * @param array $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Validates the given xliff content with the configured validation mode
     *
     * @param string $xliffContent
     * @return \LibXMLError[]
     * @throws \Symfony\Component\Filesystem\Exception\FileNotFoundException
     */
    public function validate($xliffContent)
    {
        $errors = [];

        switch ($this->getValidationMode()) {
            case static::MODE_STRICT:
                $schema = 'xliff-core-1.2-strict.xsd';
                break;
            case static::MODE_TRANSITIONAL:
                $schema = 'xliff-core-1.2-transitional.xsd';
                break;
            case static::MODE_DISABLED:
            default:
                $schema = null;
                break;
        }

        if ($schema === null) {
            return $errors;
        }

        // retrieve schema (from file or previous validation calls)
        $this->schemaContent = $this->schemaContent ?: file_get_contents(__DIR__ . '/../Resources/schema/' . $schema);
        if ($this->schemaContent === null) {
            throw new FileNotFoundException('Schema could not be loaded!');
        }

        // validate xliff against schema
        libxml_use_internal_errors(true);
        $xmlDOM = new DOMDocument();
        $xmlDOM->loadXML($xliffContent);
        if (!$xmlDOM->schemaValidateSource($this->schemaContent)) {
            $errors = libxml_get_errors();
            libxml_clear_errors();
        }

        // free up some memory
        unset($xmlDOM);

        return $errors;
    }
    /**
     * Return the configured validation mode
     *
     * @return mixed
     */
    private function getValidationMode()
    {
        return $this->config['xliff']['validation'];
    }
}