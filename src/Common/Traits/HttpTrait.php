<?php
/**
 * User: Ryan
 * Date: 2019/3/7
 * Time: 14:58
 */

namespace JDSDK\Common\Traits;

/**
 * Trait HttpTrait
 * @package common\components\jd\Common\Traits
 */
trait HttpTrait
{
    /**
     * 拼接请求url
     * @param $apiUrl
     * @param array $params
     * @return string
     */
    public static function buildApiUrl($apiUrl, array $params)
    {
        return $apiUrl . '?' . self::buildUrlParams($params);
    }

    /**
     * 拼装url参数
     * @param array $params
     * @return string
     */
    public static function buildUrlParams(array $params)
    {
        $params = array_filter($params);
        return http_build_query($params);
    }

    /**
     * 发起请求，返回响应结果
     * @param $url
     * @param array $data
     * @param string $method
     * @param array $headers
     * @return mixed
     */
    public static function request($url, $data = [], $method = 'POST', $headers = [])
    {
        $httpClient = \Yii::$app->http;
        $request = $httpClient->createRequest()
            ->setUrl($url)
            ->setHeaders($headers)
            ->setMethod($method)
            ->setData($data);

        $response = $request->send();
        return $response->data;
    }
}