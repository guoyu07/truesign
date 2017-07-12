<?php


namespace Royal\Util;

use Guzzle\Http\Client;
use Royal\Logger\Logger;

class Http {
    function getJson($uri, $params=array(), $throwException = true) {
        try {
            $client = new Client();
            $response = $client->get($uri, null, array('query'=>$params, 'timeout'=>15))->send();
            Logger::debug('HTTP_GET_JSON', array('uri'=>$uri, 'response'=>$response->getBody(true),'params'=>$params));
            $json = json_decode($response->getBody(true), true);
            $error = json_last_error();
            if ($error) {
                Logger::critical('HTTP_JSON_PARSE_ERROR',
                    array('uri'=>$uri,
                        'response'=>$response->getBody(true),
                        'params'=>$params));
                if ($throwException) {
                    throw new \Exception($uri.' json error', -5321);
                }
            } else {
                return $json;
            }
        } catch (\Exception $e) {
            Logger::critical('HTTP_JSON_EXCEPTION',
                array('uri'=>$uri,
                    'params'=>$params,
                    'error'=>$e->getMessage()
                ));
            if ($throwException) {
                throw new \Exception($uri.': '.$e->getMessage(), -5322);
            }
        }
    }
} 