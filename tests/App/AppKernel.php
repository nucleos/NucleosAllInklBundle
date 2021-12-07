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
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

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

    public function registerBundles(): iterable
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

    protected function configureRoutes($routes): void
    {
        if ($routes instanceof RoutingConfigurator) {
            $routes
                ->add('test', '/test')
                ->controller(TestController::class)
            ;

            return;
        }

        $routes->add('/test', TestController::class);
    }

    protected function configureContainer($container, $loader): void
    {
        if ($container instanceof ContainerConfigurator) {
            $container->import(__DIR__.'/config/config.yaml');

            return;
        }

        $loader->load(__DIR__.'/config/config.yaml');
    }
}
