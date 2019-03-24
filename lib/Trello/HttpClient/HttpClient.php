<?php

namespace Trello\HttpClient;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;
use Trello\Client;
use Trello\Exception\ErrorException;
use Trello\Exception\RuntimeException;
use Trello\HttpClient\Listener\AuthListener;
use Trello\HttpClient\Listener\ErrorListener;
//use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class HttpClient implements HttpClientInterface
{
    protected $options = array(
        'base_uri'    => 'https://api.trello.com/',
        'headers' => [
            'User-Agent' => 'php-trello-api (http://github.com/cdaguerre/php-trello-api)'
        ],
        'timeout'     => 10,
        'api_version' => 1
    );

    /**
     * @var ClientInterface
     */
    protected $client;
    protected $handlerStack;

    protected $headers = array();

    private $lastResponse;
    private $lastRequest;

    /**
     * @param array           $options
     * @param ClientInterface $client
     */
    public function __construct(array $options = array(), ClientInterface $client = null)
    {
        $this->options = array_merge($this->options, $options);

        $this->handlerStack = new HandlerStack();
        $this->handlerStack->setHandler(\GuzzleHttp\choose_handler());

        $client = $client ?: new GuzzleClient(['handler' => $this->handlerStack]);

        $this->client  = $client;

        //$this->addListener('request.error', array(new ErrorListener($this->options), 'onRequestError'));

        $this->clearHeaders();
    }

    /**
     * {@inheritDoc}
     */
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function setHeaders(array $headers)
    {
        $this->headers = array_merge($this->headers, $headers);
    }

    /**
     * Clears used headers
     */
    public function clearHeaders()
    {
        $this->headers = array(
            'Accept' => sprintf('application/vnd.orcid.%s+json', $this->options['api_version']),
            'User-Agent' => sprintf('%s', $this->options['user_agent']),
        );
    }

//    /**
//     * @param string $eventName
//     */
//    public function addListener($eventName, $listener)
//    {
//        $this->client->getEventDispatcher()->addListener($eventName, $listener);
//    }
//
//    public function addSubscriber(EventSubscriberInterface $subscriber)
//    {
//        $this->client->addSubscriber($subscriber);
//    }

    /**
     * {@inheritDoc}
     */
    public function get($path, array $parameters = array(), array $headers = array())
    {
        return $this->request($path, $parameters, 'GET', $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function post($path, $body = null, array $headers = array())
    {
        if (!isset($headers['Content-Type'])) {
            $headers['Content-Type'] = 'application/x-www-form-urlencoded';
        }

        return $this->request($path, $body, 'POST', $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function patch($path, $body = null, array $headers = array())
    {
        if (!isset($headers['Content-Type'])) {
            $headers['Content-Type'] = 'application/x-www-form-urlencoded';
        }

        return $this->request($path, $body, 'PATCH', $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function delete($path, $body = null, array $headers = array())
    {
        return $this->request($path, $body, 'DELETE', $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function put($path, $body, array $headers = array())
    {
        if (!isset($headers['Content-Type'])) {
            $headers['Content-Type'] = 'application/x-www-form-urlencoded';
        }

        return $this->request($path, $body, 'PUT', $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function request($path, $body = null, $httpMethod = 'GET', array $headers = array(), array $options = array())
    {
        $path = $this->options['api_version'].'/'.$path;

        // As per the Trello api, it seems all parameters are either part of the URL path or
        // query string parameters.
        if ($body && (!isset($headers['Content-type']) || (isset($headers['Content-type']) && $headers['Content-type'] !== 'application/json'))) {
            $path .= (false === strpos($path, '?') ? '?' : '&');
            $path .= utf8_encode(http_build_query($body, '', '&'));
        }

        $requestOptions = array_merge($this->options, $options);
        $requestOptions['headers'] = array_merge($requestOptions['headers'],$this->headers, $headers);
        $requestOptions['body'] = !empty($body) ? json_encode($body) : null;

        try {
            $response = $this->client->request($httpMethod, $path, $requestOptions);
        } catch (\LogicException $e) {
            throw new ErrorException($e->getMessage(), $e->getCode(), 1, __FILE__, __LINE__, $e);
        } catch (\RuntimeException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

//        $this->lastRequest  = $request;
        $this->lastResponse = $response;

        return $response;
    }

    /**
     * {@inheritDoc}
     */
    public function authenticate($tokenOrLogin, $password = null, $method)
    {
        $this->handlerStack->remove('auth');

        $this->handlerStack->push(Middleware::mapRequest(function (RequestInterface $r) use ($tokenOrLogin, $password, $method) {
            // Skip by default
            if (null === $method) {
                return;
            }

            switch ($method) {
                case Client::AUTH_HTTP_PASSWORD:
                    return $r->withHeader(
                        'Authorization',
                        sprintf('Basic %s', base64_encode($tokenOrLogin.':'.$password))
                    );
                    break;

                case Client::AUTH_HTTP_TOKEN:
                    return $r->withHeader(
                        'Authorization',
                        sprintf('token %s', $tokenOrLogin)
                    );
                    break;

                case Client::AUTH_URL_CLIENT_ID:
                    $url = $r->getUri();

                    $parameters = array(
                        'key'   => $tokenOrLogin,
                        'token' => $password,
                    );

                    $url .= (false === strpos($url, '?') ? '?' : '&');
                    $url .= utf8_encode(http_build_query($parameters, '', '&'));

                    return $r->withUri(new Uri($url));

                    break;

                case Client::AUTH_URL_TOKEN:
                    $url = $r->getUri();
                    $url .= (false === strpos($url, '?') ? '?' : '&');
                    $url .= utf8_encode(http_build_query(
                        array('token' => $tokenOrLogin, 'key' => $password),
                        '',
                        '&'
                    ));

                    return $r->withUri($url);
                    break;

                default:
                    throw new RuntimeException(sprintf('%s not yet implemented', $this->method));
            }
        }),'auth');

        // FIXME: VERIFY THAT I DON'T NEED TO RECREATE THE GUZZLECLIENT

    }

    /**
     * @return Request
     */
    public function getLastRequest()
    {
        return $this->lastRequest;
    }

    /**
     * @return Response
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

}
