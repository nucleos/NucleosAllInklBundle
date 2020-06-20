<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace  Nucleos\AllInklBundle\Model;

final class Session
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $token;

    public function __construct(string $username, string $token)
    {
        $this->username = $username;
        $this->token    = $token;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
