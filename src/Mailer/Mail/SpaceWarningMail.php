<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\AllInklBundle\Mailer\Mail;

use Nucleos\AllInklBundle\Model\AccountWarning;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Header\Headers;
use Symfony\Component\Mime\Part\AbstractPart;

final class SpaceWarningMail extends TemplatedEmail
{
    /**
     * @var AccountWarning|null
     */
    private $accountWarning;

    public function __construct(Headers $headers = null, AbstractPart $body = null)
    {
        parent::__construct($headers, $body);

        $this->htmlTemplate('@NucleosAllInkl/Mail/warning.html.twig');
    }

    public function getAccountWarning(): ?AccountWarning
    {
        return $this->accountWarning;
    }

    /**
     * @return SpaceWarningMail
     */
    public function setAccountWarning(?AccountWarning $accountWarning): self
    {
        $this->accountWarning = $accountWarning;

        return $this;
    }

    /**
     * @return array<mixed>
     */
    public function getContext(): array
    {
        return array_merge([
            'accountWarning' => $this->getAccountWarning(),
        ], parent::getContext());
    }
}
