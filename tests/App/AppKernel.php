<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\AllInklBundle\Tests\App;

use Nucleos\AllInklBundle\NucleosAllInklBundle;
use Nucleos\AllInklBundle\Tests\App\Controller\TestController;
use Nucleos\Twig\Bridge\Symfony\Bundle\NucleosTwigBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

final class AppKernel extends Kernel
{
    use MicroKernelTrait;

    /**
     * @var string
     */
    private $baseDir;

    public function __construct()
    {
        $this->baseDir = sprintf('%s/%s/app-bundle/var/', sys_get_temp_dir(), uniqid('', true));

        parent::__construct('test', false);
    }

    public function registerBundles()
    {
        yield new FrameworkBundle();
        yield new TwigBundle();
        yield new NucleosTwigBundle();
        yield new NucleosAllInklBundle();
    }

    public function getCacheDir(): string
    {
        return $this->baseDir.'cache';
    }

    public function getLogDir(): string
    {
        return $this->baseDir.'log';
    }

    public function getProjectDir(): string
    {
        return __DIR__;
    }

    protected function configureRoutes(RouteCollectionBuilder $routes): void
    {
        $routes->add('/test', TestController::class);
    }

    protected function configureContainer(ContainerBuilder $containerBuilder, LoaderInterface $loader): void
    {
        $loader->load(__DIR__.'/config/config.yaml');
    }
}
