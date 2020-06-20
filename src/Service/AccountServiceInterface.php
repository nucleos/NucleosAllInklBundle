<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\AllInklBundle\Service;

use Nucleos\AllInklBundle\Model\Session;

interface AccountServiceInterface
{
    /**
     * Get a list of all accounts.
     *
     * @param Session     $session session
     * @param string|null $account account name
     */
    public function getAccounts(Session $session, string $account = null): array;
}
