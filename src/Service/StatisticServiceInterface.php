<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\AllInklBundle\Service;

use Nucleos\AllInklBundle\Exception\AllInklException;
use Nucleos\AllInklBundle\Model\Session;

interface StatisticServiceInterface
{
    /**
     * Reads space information.
     *
     * @param bool $details
     *
     * @return array<array<string, mixed>>
     *
     * @throws AllInklException
     */
    public function getSpace(Session $session, bool $subAccounts = false, $details = false): array;

    /**
     * Get space usage information.
     *
     * @throws AllInklException
     */
    public function getSpaceUsage(Session $session, string $directory): array;

    /**
     * Get traffic information.
     *
     * @param int|null $month
     *
     * @throws AllInklException
     */
    public function getTraffic(Session $session, int $year = null, $month = null): array;
}
