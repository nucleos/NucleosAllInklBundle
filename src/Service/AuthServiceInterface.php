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

interface AuthServiceInterface
{
    /**
     * Creates a new API session.
     *
     * @param string $username        username
     * @param string $password        password, can be sha1-ed or plain
     * @param bool   $plainPassword   encoding for the password
     * @param int    $sessionLifetime lifetime in seconds
     * @param bool   $sessionRenew    auto renew on client request
     *
     * @throws AllInklException
     */
    public function createSession(
        string $username,
        string $password,
        bool $plainPassword = false,
        int $sessionLifetime = 1800,
        bool $sessionRenew = true
    ): Session;
}
