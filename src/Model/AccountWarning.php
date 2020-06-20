<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\AllInklBundle\Model;

class AccountWarning
{
    /**
     * @var string
     */
    private $account;

    /**
     * @var int
     */
    private $used;

    /**
     * @var int
     */
    private $available;

    public function __construct(string $account, int $used, int $available)
    {
        $this->account   = $account;
        $this->used      = $used;
        $this->available = $available;
    }

    public function getAccount(): string
    {
        return $this->account;
    }

    public function getUsed(): int
    {
        return $this->used;
    }

    public function getAvailable(): int
    {
        return $this->available;
    }
}
