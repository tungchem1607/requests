<?php
/**
 * Project requests.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 10/16/18
 * Time: 17:04
 */

namespace nguyenanhung\MyRequests;

use nguyenanhung\MyRequests\Interfaces\BackgroundRequestInterface;
use nguyenanhung\MyRequests\Interfaces\ProjectInterface;

/**
 * Class BackgroundRequest
 *
 * @package    nguyenanhung\MyRequests
 * @author     713uk13m <dev@nguyenanhung.com>
 * @copyright  713uk13m <dev@nguyenanhung.com>
 */
class BackgroundRequest implements ProjectInterface, BackgroundRequestInterface
{
    /**
     * BackgroundRequest constructor.
     */
    public function __construct()
    {
    }

    /**
     * Function getVersion
     *
     * @author  : 713uk13m <dev@nguyenanhung.com>
     * @time    : 10/7/18 02:24
     *
     * @return mixed|string Current Project Version
     * @example string 0.1.3
     */
    public function getVersion()
    {
        return self::VERSION;
    }

    /**
     * Hàm gọi 1 async GET Request để không delay Main Process
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/16/18 17:15
     *
     * @param string $url Url Endpoint
     *
     * @return bool TRUE nếu thành công, FALSE nếu thất bại
     */
    public static function backgroundHttpGet($url)
    {
        $parts = parse_url($url);
        if ($parts['scheme'] == 'https') {
            $fp = fsockopen('ssl://' . $parts['host'], isset($parts['port']) ? $parts['port'] : 443, $errno, $errstr, 30);
        } else {
            $fp = fsockopen($parts['host'], isset($parts['port']) ? $parts['port'] : 80, $errno, $errstr, 30);
        }
        if (!$fp) {
            return FALSE;
        } else {
            $out = "GET " . $parts['path'] . "?" . $parts['query'] . " HTTP/1.1\r\n";
            $out .= "Host: " . $parts['host'] . "\r\n";
            $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $out .= "Connection: Close\r\n\r\n";
            fwrite($fp, $out);
            fclose($fp);

            return TRUE;
        }
    }

    /**
     * Hàm gọi 1 async POST Request để không delay Main Process
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/16/18 17:16
     *
     * @param string $url         Url Endpoint
     * @param string $paramString Params to Request
     *
     * @return bool TRUE nếu thành công, FALSE nếu thất bại
     */
    public static function backgroundHttpPost($url, $paramString = '')
    {
        $parts = parse_url($url);
        if ($parts['scheme'] == 'https') {
            $fp = fsockopen('ssl://' . $parts['host'], isset($parts['port']) ? $parts['port'] : 443, $errno, $errstr, 30);
        } else {
            $fp = fsockopen($parts['host'], isset($parts['port']) ? $parts['port'] : 80, $errno, $errstr, 30);
        }
        if (!$fp) {
            return FALSE;
        } else {
            $out = "POST " . $parts['path'] . "?" . $parts['query'] . " HTTP/1.1\r\n";
            $out .= "Host: " . $parts['host'] . "\r\n";
            $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $out .= "Content-Length: " . strlen($paramString) . "\r\n";
            $out .= "Connection: Close\r\n\r\n";
            if ($paramString != '') {
                $out .= $paramString;
            }
            fwrite($fp, $out);
            fclose($fp);

            return TRUE;
        }
    }
}
