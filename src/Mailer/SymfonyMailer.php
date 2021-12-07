<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\AllInklBundle\Mailer;

use Nucleos\AllInklBundle\Mailer\Mail\SpaceWarningMail;
use Nucleos\AllInklBundle\Model\AccountWarning;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;

final class SymfonyMailer implements Mailer
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var array<string, mixed>
     */
    private $emails;

    public function __construct(MailerInterface $mailer, TranslatorInterface $translator, array $emails)
    {
        $this->mailer     = $mailer;
        $this->translator = $translator;
        $this->emails     = $emails;
    }

    public function sendSpaceWarning(AccountWarning $warning): bool
    {
        if (!\array_key_exists('warning', $this->emails)) {
            return true;
        }

        $mail = (new SpaceWarningMail())
            ->from(Address::create($this->emails['warning']['from']))
            ->to(Address::create($this->emails['warning']['to']))
            ->subject($this->translator->trans('space_warning.subject', [
                '%account%' => $warning->getAccount(),
            ], 'NucleosAllInklBundle'))
            ->setAccountWarning($warning)
        ;

        try {
            $this->mailer->send($mail);

            return true;
        } catch (TransportExceptionInterface $exception) {
            return false;
        }
    }
}
