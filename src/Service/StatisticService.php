<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\AllInklBundle\Service;

use DateTime;
use Nucleos\AllInklBundle\Model\Session;

final class StatisticService extends AbstractService implements StatisticServiceInterface
{
    public function getSpace(Session $session, bool $subAccounts = false, $details = false): array
    {
        $result = $this->call($session, 'get_space', [
            'show_subaccounts' => $subAccounts ? 'Y' : 'N',
            'show_details'     => $details ? 'Y' : 'N',
        ]);

        return array_map(static function ($item) {
            $item['last_calculation'] = new DateTime('@'.$item['last_calculation']);
            $item['used_htdocs_space']      *= 1000;
            $item['used_chroot_space']      *= 1000;
            $item['used_database_space']    *= 1000;
            $item['used_mailaccount_space'] *= 1000;
            $item['used_webspace']          *= 1000;
            $item['max_webspace']           *= 1000;

            return $item;
        }, $result);
    }

    public function getSpaceUsage(Session $session, string $directory): array
    {
        return $this->call($session, 'get_space_usage', [
            'directory' => $directory,
        ]);
    }

    public function getTraffic(Session $session, int $year = null, $month = null): array
    {
        return $this->call($session, 'get_traffic', [
            'year'  => $year,
            'month' => $month,
        ]);
    }
}
