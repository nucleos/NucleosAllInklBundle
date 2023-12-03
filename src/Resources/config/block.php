<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Nucleos\AllInklBundle\Block\Service\SpaceStatisticBlockService;
use Symfony\Component\DependencyInjection\Reference;

return static function (ContainerConfigurator $container): void {
    $container->services()

        ->set('nucleos_allinkl.block.space_statistic', SpaceStatisticBlockService::class)
            ->tag('sonata.block')
            ->args([
                new Reference('twig'),
                new Reference('nucleos_allinkl.service.auth'),
                new Reference('nucleos_allinkl.service.statistic'),
            ])
            ->call('setLogger', [
                new Reference('logger'),
            ])
    ;
};
