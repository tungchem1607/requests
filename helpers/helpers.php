<?php
/**
 * Project requests
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 08/03/2021
 * Time: 23:16
 */
if (!function_exists('sendSimpleRequest')) {
    /**
     * Function sendSimpleRequest
     *
     * @param string       $url    URL Target Endpoint
     * @param string|array $data   Array Data to Request
     * @param string       $method GET or POST
     *
     * @return bool|string|null
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 08/03/2021 20:38
     */
    function sendSimpleRequest($url = '', $data = [], $method = 'GET')
    {
        $target = (!empty($data) && (is_array($data) || is_object($data))) ? $url . '?' . http_build_query($data) : $url;
        $method = strtoupper($method);
        $curl   = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL            => $target,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_POSTFIELDS     => "",
            CURLOPT_HTTPHEADER     => array(),
        ));
        $response = curl_exec($curl);
        $err      = curl_error($curl);
        curl_close($curl);
        if ($err) {
            $message = "cURL Error #: " . $err;
            if (function_exists('log_message')) {
                log_message('error', $message);
            }

            return NULL;
        } else {
            return $response;
        }
    }
}
