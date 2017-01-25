<?php

namespace Decline\TransformatBundle\Exception;

/**
 * Class DuplicateKeyException
 * @package Decline\TransformatBundle\Exception
 */
class DuplicateKeyException extends \Exception
{

    /**
     * DuplicateKeyException constructor.
     * @param string $key
     * @param int $fileName
     */
    public function __construct($key, $fileName)
    {
        parent::__construct(
            sprintf(
                'Duplicate translation key "%s" found in File %s',
                $key,
                $fileName
            ),
            0,
            null
        );
    }
}