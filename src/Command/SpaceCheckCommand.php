<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\AllInklBundle\Command;

use Nucleos\AllInklBundle\Exception\AllInklException;
use Nucleos\AllInklBundle\Mailer\Mailer;
use Nucleos\AllInklBundle\Model\AccountWarning;
use Nucleos\AllInklBundle\Service\AuthService;
use Nucleos\AllInklBundle\Service\StatisticService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class SpaceCheckCommand extends Command
{
    /**
     * @var AuthService
     */
    private $authService;

    /**
     * @var StatisticService
     */
    private $statisticService;

    /**
     * @var array<mixed>
     */
    private $defaultAccounts;

    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var int
     */
    private $warningLevel;

    public function __construct(
        AuthService $authService,
        StatisticService $statisticService,
        Mailer $mailer,
        array $defaultAccounts,
        int $warningLevel
    ) {
        $this->authService      = $authService;
        $this->statisticService = $statisticService;
        $this->mailer           = $mailer;
        $this->defaultAccounts  = $defaultAccounts;
        $this->warningLevel     = $warningLevel;

        parent::__construct();
    }

    public function configure(): void
    {
        $this->setName('allinkl:check');
        $this->setDescription('Performs a space check');
        $this->addOption('dry-run', null, InputOption::VALUE_NONE, 'Starts in silent mode without sending any mail');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output = new SymfonyStyle($input, $output);
        $output->title('Checking All-Inkl accounts:');

        try {
            $accounts = $this->getAccountData();
        } catch (AllInklException $ce) {
            $output->error('Error retrieving All-Inkl for login: '.$ce->getMessage());

            return 1;
        }

        $rows = [];

        $warning = (100 + $this->warningLevel) / 100;

        foreach ($accounts as $account) {
            $ratio = $account->getUsed() / $account->getAvailable();

            $rows[] = [$account->getAccount(), number_format($ratio * 100, 2).'%'];

            if ($ratio < $warning) {
                continue;
            }

            $output->warning(sprintf('Webspace limit reached for "%s"', $account->getAccount()));

            $this->mailer->sendSpaceWarning($account);
        }

        $output->table(['Account', 'Used'], $rows);

        return 0;
    }

    /**
     * @throws AllInklException
     *
     * @return AccountWarning[]
     */
    private function getAccountData(): array
    {
        $accounts = [];

        foreach ($this->defaultAccounts as $account) {
            $session = $this->authService->createSession($account['login'], $account['password']);

            $accounts = array_merge_recursive($accounts, $this->statisticService->getSpace($session, true, true));
        }

        return array_map(static function ($item): AccountWarning {
            return new AccountWarning($item['account_login'], $item['used_webspace'], $item['max_webspace']);
        }, $accounts);
    }
}
