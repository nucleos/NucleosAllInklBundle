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
use SoapClient;
use SoapFault;

final class AuthService extends AbstractService implements AuthServiceInterface
{
    /**
     * Default Endpoint.
     */
    private const DEFAULT_AUTH_ENDPOINT = 'https://kasapi.kasserver.com/soap/wsdl/KasAuth.wsdl';

    /**
     * @param string $endpoint
     */
    public function __construct(string $endpoint = null)
    {
        if (null === $endpoint) {
            $endpoint = static::DEFAULT_AUTH_ENDPOINT;
        }

        parent::__construct($endpoint);
    }

    public function createSession(
        string $username,
        string $password,
        bool $plainPassword = false,
        int $sessionLifetime = 1800,
        bool $sessionRenew = true
    ): Session {
        try {
            $soap = new SoapClient($this->getEndpoint());

            $result = $soap->__soapCall('KasAuth', [
                json_encode(
                    [
                        'KasUser'               => $username,
                        'KasAuthType'           => 'sha1',
                        'KasPassword'           => $plainPassword ? sha1($password) : $password,
                        'SessionLifeTime'       => $sessionLifetime,
                        'SessionUpdateLifeTime' => $sessionRenew ? 'Y' : 'N',
                    ]
                ), ]);

            return new Session($username, $result);
        } catch (SoapFault $fault) {
            throw new AllInklException($fault->faultstring, $fault->getCode(), $fault);
        }
    }
}
