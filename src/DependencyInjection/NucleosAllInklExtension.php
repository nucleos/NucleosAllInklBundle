<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\AllInklBundle\DependencyInjection;

use Nucleos\AllInklBundle\Mailer\SymfonyMailer;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class NucleosAllInklExtension extends Extension
{
    public function getAlias()
    {
        return 'nucleos_allinkl';
    }

    /**
     * @param array<mixed> $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('block.xml');
        $loader->load('commands.xml');
        $loader->load('services.xml');

        $this->configureMail($container, $config);
        $this->configureCheck($container, $config);
        $this->configureApi($container, $config);
    }

    /**
     * @param array<mixed> $config
     */
    private function configureApi(ContainerBuilder $container, array $config): void
    {
        $container->setParameter('nucleos_allinkl.api.auth_endpoint', $config['api']['auth_endpoint']);
        $container->setParameter('nucleos_allinkl.api.api_endpoint', $config['api']['api_endpoint']);
    }

    /**
     * @param array<mixed> $config
     */
    private function configureMail(ContainerBuilder $container, array $config): void
    {
        if (!\array_key_exists('mail', $config)) {
            return;
        }

        $container->getDefinition(SymfonyMailer::class)
            ->replaceArgument(2, [
                'warning' => $config['mail'],
            ])
        ;
    }

    /**
     * @param array<mixed> $config
     */
    private function configureCheck(ContainerBuilder $container, array $config): void
    {
        $container->setParameter('nucleos_allinkl.check.warning', $config['check']['warning']);
        $container->setParameter('nucleos_allinkl.check.accounts', $config['check']['accounts']);
    }
}
