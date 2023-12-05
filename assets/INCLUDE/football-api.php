<?php

function retryWithExponentialBackoff($url, $header, $maxRetries = 1, $initialWaitTime = 2)
{
    $retries = 0;
    $waitTime = $initialWaitTime;
    while ($retries < $maxRetries) {
        $stream_context = stream_context_create(['http' => ['method' => 'GET', 'header' => $header]]);
        $response = file_get_contents($url, false, $stream_context);

        if ($response !== false) {
            return json_decode($response);
        }
        $httpStatus = $http_response_header[0];
        if (strpos($httpStatus, '429' == false)) {
            sleep($waitTime);

            $waitTime *= 2;

            $retries++;
        } else {
            /* Se caso exista outro error  */
            return null;
        }
    }
    return null;
}

function conexaoAPI($_url, $_chave)
{
    return retryWithExponentialBackoff($_url, $_chave);
}
