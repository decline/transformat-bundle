<?php

namespace Decline\TransformatBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class DeclineTransformatBundle
 * @package Decline\TransformatBundle
 */
class DeclineTransformatBundle extends Bundle
{
    public function build(ContainerBuilder $container) {
        parent::build($container);
    }
}
