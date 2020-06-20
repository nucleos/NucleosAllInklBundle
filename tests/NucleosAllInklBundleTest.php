<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\AllInklBundle\Tests;

use Nucleos\AllInklBundle\DependencyInjection\NucleosAllInklExtension;
use Nucleos\AllInklBundle\NucleosAllInklBundle;
use PHPUnit\Framework\TestCase;

final class NucleosAllInklBundleTest extends TestCase
{
    public function testGetContainerExtension(): void
    {
        $bundle = new NucleosAllInklBundle();

        static::assertInstanceOf(NucleosAllInklExtension::class, $bundle->getContainerExtension());
    }
}
