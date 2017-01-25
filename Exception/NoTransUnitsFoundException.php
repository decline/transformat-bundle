<?php

namespace Decline\TransformatBundle\Exception;

/**
 * Class NoTransUnitsFoundException
 * @package Decline\TransformatBundle\Exception
 */
class NoTransUnitsFoundException extends \Exception
{

    /**
     * NoTransUnitsFoundException constructor.
     * @param string $fileName
     */
    public function __construct($fileName)
    {
        parent::__construct(
            sprintf(
                'No trans-units could be found in "%s". Is the file empty or did you define a wrong namespace?',
                $fileName
            ),
            0,
            null
        );
    }
}