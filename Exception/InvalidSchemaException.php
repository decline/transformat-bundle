<?php

namespace Decline\TransformatBundle\Exception;

/**
 * Class InvalidSchemaException
 * @package Decline\TransformatBundle\Exception
 */
class InvalidSchemaException extends \Exception
{
    /**
     * InvalidSchemaException constructor.
     * @param string $error
     * @param int $fileName
     */
    public function __construct($error, $fileName)
    {
        parent::__construct(
            sprintf(
                'The following error was found whie validating the File %s: "%s"',
                $fileName,
                $error
            ),
            0,
            null
        );
    }
}