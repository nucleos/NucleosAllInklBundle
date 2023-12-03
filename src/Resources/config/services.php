<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Nucleos\AllInklBundle\Mailer\Mailer;
use Nucleos\AllInklBundle\Mailer\SymfonyMailer;
use Nucleos\AllInklBundle\Service\AccountService;
use Nucleos\AllInklBundle\Service\AuthService;
use Nucleos\AllInklBundle\Service\StatisticService;
use Symfony\Component\DependencyInjection\Parameter;
use Symfony\Component\DependencyInjection\Reference;

return static function (ContainerConfigurator $container): void {
    $container->services()

        ->set(SymfonyMailer::class)
            ->args([
                new Reference('mailer.mailer'),
                new Reference('translator'),
                [],
            ])

        ->alias(Mailer::class, SymfonyMailer::class)

        ->set('nucleos_allinkl.service.account', AccountService::class)
            ->args([
                new Parameter('nucleos_allinkl.api.api_endpoint'),
            ])

        ->set('nucleos_allinkl.service.auth', AuthService::class)
            ->args([
                new Parameter('nucleos_allinkl.api.api_endpoint'),
            ])

        ->set('nucleos_allinkl.service.statistic', StatisticService::class)
            ->args([
                new Parameter('nucleos_allinkl.api.api_endpoint'),
            ])
    ;
};
