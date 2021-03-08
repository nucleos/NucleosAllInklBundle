<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\AllInklBundle\Block\Service;

use Nucleos\AllInklBundle\Exception\AllInklException;
use Nucleos\AllInklBundle\Service\AuthServiceInterface;
use Nucleos\AllInklBundle\Service\StatisticServiceInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Sonata\BlockBundle\Block\Service\EditableBlockService;
use Sonata\BlockBundle\Form\Mapper\FormMapper;
use Sonata\BlockBundle\Meta\Metadata;
use Sonata\BlockBundle\Meta\MetadataInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\Form\Type\ImmutableArrayType;
use Sonata\Form\Validator\ErrorElement;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;

final class SpaceStatisticBlockService extends AbstractBlockService implements EditableBlockService, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var AuthServiceInterface
     */
    private $authService;

    /**
     * @var StatisticServiceInterface
     */
    private $statisticService;

    public function __construct(Environment $twig, AuthServiceInterface $authService, StatisticServiceInterface $statisticService)
    {
        parent::__construct($twig);

        $this->authService      = $authService;
        $this->statisticService = $statisticService;
        $this->logger           = new NullLogger();
    }

    public function execute(BlockContextInterface $blockContext, Response $response = null): Response
    {
        \assert(null !== $blockContext->getTemplate());

        return $this->renderResponse($blockContext->getTemplate(), [
            'context'  => $blockContext,
            'settings' => $blockContext->getSettings(),
            'block'    => $blockContext->getBlock(),
            'data'     => $this->getData($blockContext->getSettings()),
        ], $response);
    }

    public function configureCreateForm(FormMapper $form, BlockInterface $block): void
    {
        $this->configureEditForm($form, $block);
    }

    public function configureEditForm(FormMapper $formMapper, BlockInterface $block): void
    {
        $formMapper->add('settings', ImmutableArrayType::class, [
            'keys' => [
                ['title', TextType::class, [
                    'required' => false,
                    'label'    => 'form.label_title',
                ]],
                ['translation_domain', TextType::class, [
                    'label'    => 'form.label_translation_domain',
                    'required' => false,
                ]],
                ['icon', TextType::class, [
                    'label'    => 'form.label_icon',
                    'required' => false,
                ]],
                ['class', TextType::class, [
                    'label'    => 'form.label_class',
                    'required' => false,
                ]],
                ['login', TextType::class, [
                    'required' => false,
                    'label'    => 'form.label_login',
                ]],
                ['password', TextType::class, [
                    'required' => false,
                    'label'    => 'form.label_password',
                ]],
            ],
            'translation_domain' => 'NucleosAllInklBundle',
        ]);
    }

    public function configureSettings(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'title'              => null,
            'translation_domain' => null,
            'icon'               => 'fa fa-bar-chart-o',
            'class'              => null,
            'login'              => null,
            'password'           => null,
            'template'           => '@NucleosAllInkl/Block/block_space_statistic.html.twig',
        ]);

        $resolver->setRequired(['login', 'password']);
    }

    public function validate(ErrorElement $errorElement, BlockInterface $block): void
    {
    }

    public function getMetadata(): MetadataInterface
    {
        return new Metadata('nucleos_allinkl.block.space_statistic', null, null, 'NucleosAllInklBundle', [
            'class' => 'fa fa-area-chart',
        ]);
    }

    public function getName(): string
    {
        return $this->getMetadata()->getTitle();
    }

    private function getData(array $settings = []): ?array
    {
        try {
            $session = $this->authService->createSession($settings['login'], $settings['password']);

            return $this->statisticService->getSpace($session, true, true);
        } catch (AllInklException $ce) {
            $this->logger->warning('Error retrieving All-Inkl for login: '.$settings['login'], [
                'exception' => $ce,
            ]);
        }

        return null;
    }
}
