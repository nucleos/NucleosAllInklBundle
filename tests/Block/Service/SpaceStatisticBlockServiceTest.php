<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\AllInklBundle\Tests\Block\Service;

use Nucleos\AllInklBundle\Block\Service\SpaceStatisticBlockService;
use Nucleos\AllInklBundle\Model\Session;
use Nucleos\AllInklBundle\Service\AuthServiceInterface;
use Nucleos\AllInklBundle\Service\StatisticServiceInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Sonata\BlockBundle\Block\BlockContext;
use Sonata\BlockBundle\Model\Block;
use Sonata\BlockBundle\Test\BlockServiceTestCase;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class SpaceStatisticBlockServiceTest extends BlockServiceTestCase
{
    /**
     * @var Environment&MockObject
     */
    protected $twig;

    /**
     * @var AuthServiceInterface&MockObject
     */
    private $authService;

    /**
     * @var MockObject&StatisticServiceInterface
     */
    private $statisticService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->twig              = $this->createMock(Environment::class);
        $this->authService       = $this->createMock(AuthServiceInterface::class);
        $this->statisticService  = $this->createMock(StatisticServiceInterface::class);
    }

    public function testExecute(): void
    {
        $session = new Session('foo', 'token');

        $this->authService->expects(static::once())->method('createSession')
            ->with(static::equalTo('foologin'), static::equalTo('barpw'))
            ->willReturn($session)
        ;

        $dataResponse = [
            ['foo' => 'bar'],
        ];

        $this->statisticService->expects(static::once())->method('getSpace')
            ->with(static::equalTo($session), true, true)
            ->willReturn($dataResponse)
        ;

        $block = new Block();

        $blockContext = new BlockContext($block, [
            'title'        => null,
            'login'        => 'foologin',
            'password'     => 'barpw',
            'template'     => '@NucleosAllInkl/Block/block_space_statistic.html.twig',
        ]);

        $response = new Response();

        $this->twig->expects(static::once())->method('render')
            ->with(
                '@NucleosAllInkl/Block/block_space_statistic.html.twig',
                [
                    'context'    => $blockContext,
                    'settings'   => $blockContext->getSettings(),
                    'block'      => $blockContext->getBlock(),
                    'data'       => $dataResponse,
                ]
            )
            ->willReturn('TWIG_CONTENT')
        ;

        $blockService = new SpaceStatisticBlockService(
            $this->twig,
            $this->authService,
            $this->statisticService
        );

        static::assertSame($response, $blockService->execute($blockContext, $response));
        static::assertSame('TWIG_CONTENT', $response->getContent());
    }

    public function testDefaultSettings(): void
    {
        $blockService = new SpaceStatisticBlockService(
            $this->twig,
            $this->authService,
            $this->statisticService
        );
        $blockContext = $this->getBlockContext($blockService);

        $this->assertSettings([
            'title'              => null,
            'translation_domain' => null,
            'icon'               => 'fa fa-bar-chart-o',
            'class'              => null,
            'login'              => null,
            'password'           => null,
            'template'           => '@NucleosAllInkl/Block/block_space_statistic.html.twig',
        ], $blockContext);
    }
}
