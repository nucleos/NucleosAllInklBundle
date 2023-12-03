<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\AllInklBundle\Service;

use Exception;
use Nucleos\AllInklBundle\Exception\AllInklException;
use Nucleos\AllInklBundle\Model\Session;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use SoapClient;
use SoapFault;

abstract class AbstractService implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * Default Endpoint.
     */
    private const DEFAULT_API_ENDPOINT = 'https://kasapi.kasserver.com/soap/wsdl/KasApi.wsdl';

    /**
     * @var string
     */
    private $endpoint;

    public function __construct(string $endpoint = null)
    {
        if (null === $endpoint) {
            $endpoint = self::DEFAULT_API_ENDPOINT;
        }

        $this->endpoint = $endpoint;
        $this->logger   = new NullLogger();
    }

    protected function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * @throws AllInklException
     */
    protected function call(Session $session, string $method, array $parameter = []): array
    {
        try {
            $soap = new SoapClient($this->endpoint);

            $response = $soap->__soapCall('KasApi', [
                json_encode(
                    [
                        'KasUser'          => $session->getUsername(),
                        'KasAuthType'      => 'session',
                        'KasAuthData'      => $session->getToken(),
                        'KasRequestType'   => $method,
                        'KasRequestParams' => $this->filterNull($parameter),
                    ]
                ), ]);

            return $response['Response']['ReturnInfo'];
        } catch (SoapFault $fault) {
            throw new AllInklException($fault->faultstring, $fault->getCode(), $fault);
        } catch (Exception $e) {
            throw new AllInklException('Error parsing API response: '.$e->getMessage(), 500);
        }
    }

    /**
     * Filter null values.
     *
     * @param array $object
     */
    private function filterNull($object): array
    {
        return array_filter($object, static function ($val): bool {
            return null !== $val;
        });
    }
}
