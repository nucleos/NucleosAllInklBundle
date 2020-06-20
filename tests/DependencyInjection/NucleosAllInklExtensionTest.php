<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\AllInklBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Nucleos\AllInklBundle\DependencyInjection\NucleosAllInklExtension;
use Nucleos\AllInklBundle\Mailer\SymfonyMailer;
use Symfony\Component\DependencyInjection\Definition;

final class NucleosAllInklExtensionTest extends AbstractExtensionTestCase
{
    public function testLoadDefault(): void
    {
        $mailerService = new Definition();
        $this->setDefinition(SymfonyMailer::class, $mailerService);

        $this->setParameter('kernel.bundles', []);
        $this->load([
            'api' => [
                'auth_endpoint' => 'http://auth.endpoint',
                'api_endpoint'  => 'http://api.endpoint',
            ],
        ]);

        $this->assertContainerBuilderHasParameter('nucleos_allinkl.api.auth_endpoint', 'http://auth.endpoint');
        $this->assertContainerBuilderHasParameter('nucleos_allinkl.api.api_endpoint', 'http://api.endpoint');
    }

    protected function getContainerExtensions(): array
    {
        return [
            new NucleosAllInklExtension(),
        ];
    }
}
