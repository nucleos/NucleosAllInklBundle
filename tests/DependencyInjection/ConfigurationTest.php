<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\AllInklBundle\Tests\DependencyInjection;

use Nucleos\AllInklBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

final class ConfigurationTest extends TestCase
{
    public function testOptions(): void
    {
        $processor = new Processor();

        $config = $processor->processConfiguration(new Configuration(), []);

        $expected = [
            'api' => [
                'auth_endpoint' => 'https://kasapi.kasserver.com/soap/wsdl/KasAuth.wsdl',
                'api_endpoint'  => 'https://kasapi.kasserver.com/soap/wsdl/KasApi.wsdl',
            ],
            'check' => [
                'warning'  => -5,
                'accounts' => [],
            ],
        ];

        static::assertSame($expected, $config);
    }

    public function testCronOptions(): void
    {
        $processor = new Processor();

        $config = $processor->processConfiguration(new Configuration(), [[
            'check' => [
                'warning' => -5,
                'account' => [
                    'login'    => 'foologin',
                    'password' => 'secretpw',
                ],
            ],
            'mail' => [
                'from' => 'from@example.com',
                'to'   => 'to@example.com',
            ],
        ]]);

        $expected = [
            'check' => [
                'warning'  => -5,
                'accounts' => [
                    [
                        'login'    => 'foologin',
                        'password' => 'secretpw',
                    ],
                ],
            ],
            'mail' => [
                'from'     => 'from@example.com',
                'to'       => 'to@example.com',
            ],
            'api' => [
                'auth_endpoint' => 'https://kasapi.kasserver.com/soap/wsdl/KasAuth.wsdl',
                'api_endpoint'  => 'https://kasapi.kasserver.com/soap/wsdl/KasApi.wsdl',
            ],
        ];

        static::assertSame($expected, $config);
    }
}
