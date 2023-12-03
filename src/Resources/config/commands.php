<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Nucleos\AllInklBundle\Command\SpaceCheckCommand;
use Nucleos\AllInklBundle\Mailer\Mailer;
use Symfony\Component\DependencyInjection\Parameter;
use Symfony\Component\DependencyInjection\Reference;

return static function (ContainerConfigurator $container): void {
    $container->services()

        ->set(SpaceCheckCommand::class)
            ->tag('console.command', [
                'command' => 'allinkl:check',
            ])
            ->args([
                new Reference('nucleos_allinkl.service.auth'),
                new Reference('nucleos_allinkl.service.statistic'),
                new Reference(Mailer::class),
                new Parameter('nucleos_allinkl.check.accounts'),
                new Parameter('nucleos_allinkl.check.warning'),
            ])
    ;
};
