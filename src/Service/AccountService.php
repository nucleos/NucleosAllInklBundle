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

final class AccountService extends AbstractService implements AccountServiceInterface
{
    public function getAccounts(Session $session, string $account = null): array
    {
        return $this->call($session, 'get_accounts', [
            'account_login' => $account,
        ]);
    }
}
