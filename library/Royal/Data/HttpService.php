<?php
/**
 * User: iamsee
 * Date: 14/10/30
 * Time: PM1:34
 */

namespace Royal\Data;

use Royal\Logger\Logger;
use Royal\Prof\TimeStack;
use Royal\Validator\ParamValidator;
use \Exception;
use \ErrorException;
use Guzzle\Http\Client;

class HttpService {
    const SERVICE_PARAM_KEY = 'service_token';
    const SERVICE_PRIVATE_KEY = '3UZjPxz6dBAYHq';

    public static function isServiceRequest(\Yaf_Request_Http &$request) {
        $token = $request->get(self::SERVICE_PARAM_KEY);
        if (strlen($token) > 12) {
            $md5Part = substr($token, 0, 12);
            $timePart = substr($token, 12);

            $diff = time() - intval($timePart);
            if (abs($diff) < 1800) {
                $md5Token = md5(sprintf('%s%d', self::SERVICE_PRIVATE_KEY, $timePart));
                return substr($md5Token, 8, 12) == $md5Part;
            }
        }

        return false;
    }

    private static function serviceToken() {
        $time = time();
        $md5Token = md5(sprintf('%s%d', self::SERVICE_PRIVATE_KEY, $time));
        $serviceToken = sprintf('%s%s', substr($md5Token, 8, 12), $time);
        return $serviceToken;
    }

    public static function requestWithRequest($serviceName, \Yaf_Request_Http &$request) {
        $config = static::getConfig($serviceName);
        $params = ParamValidator::paramsFromRequest($request, $config['required'], $config['optional']);
        return static::sendRequest($serviceName, $config['uri'], $params, $config['method']);
    }

    public static function requestWithParams($serviceName, $params) {
        $config = static::getConfig($serviceName);
        return static::sendRequest($serviceName, $config['uri'], $params, $config['method']);
    }

    public static function quietRequestWithParams($serviceName, $params) {
        try {
            return static::requestWithParams($serviceName, $params);
        } catch (Exception $e) {
            return false;
        }
    }

    public static function getConfig($serviceName) {
        if(!class_exists('Yac')){
            $params['name'] = $serviceName;
            $config = static::sendRequest('service.config', 'http://services.beaver.eallcn.com/service', $params);
            return $config;
        }
        $yac = new \Yac('sc');
        $config = $yac->get($serviceName);
        if ($config) {
            return $config;
        } else {
            $params['name'] = $serviceName;
            $config = static::sendRequest('service.config', 'http://services.beaver.eallcn.com/service', $params);
            $yac->set($serviceName, $config);
            return $config;
        }
    }

    private static function sendRequest($serviceName, $uri, $params, $method = 'GET') {
        return static::request($serviceName, $uri, $params, $method);
    }

    static function request($serviceName, $uri, $params, $method = 'GET', $throwOnError = true) {
        if (!$params) {
            $params = array();
        }

        if (empty($method)) {
            $method = 'GET';
        }

        TimeStack::start(TimeStack::TAG_HTTP, array('uri'=>$uri));
        $client = new Client();
        $params[self::SERVICE_PARAM_KEY] = static::serviceToken();
        if ($method == 'GET') {
            $response = $client->get($uri, null, array('query'=>$params, 'timeout'=>15))->send();
        } else {
            $response = $client->post($uri, null, $params, array('timeout'=>15))->send();
        }
        TimeStack::end();

        $result = json_decode($response->getBody(true), true);
        $error = json_last_error();
        if ($error != 0 && $throwOnError) {
            echo $response->getBody(true);
            Logger::critical('HttpServiceError', array('uri'=>$uri, 'params'=>$params,
                'response'=>$response->getBody(true)));
            throw new ErrorException("service $serviceName error", -502);
        }

        if ($result['code'] < 0 && $throwOnError) {
            throw new ErrorException("[Service $serviceName]:" . $result['desc'], $result['code']);
        }
        return $result['data'];
    }
} 