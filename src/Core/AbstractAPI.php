<?php
/**
 * User: Ryan
 * Date: 2019/3/6
 * Time: 14:49
 */

namespace JDSDK\Core;
use JDSDK\Common\Traits\HttpTrait;
use yii\web\HttpException;

/**
 * Class AbstractAPI.
 */
abstract class AbstractAPI
{
    use HttpTrait;

    /**
     * The request token.
     *
     * @var AccessToken
     */
    protected $accessToken;

    /**
     * @var int
     */
    protected static $maxRetries = 2;

    /**
     * AbstractAPI constructor.
     * @param AccessToken $accessToken
     */
    public function __construct(AccessToken $accessToken)
    {
        $this->setAccessToken($accessToken);
    }

    /**
     * Return the current accessToken.
     *
     * @return AccessToken
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Set the request token.
     *
     * @param AccessToken $accessToken
     *
     * @return $this
     */
    public function setAccessToken(AccessToken $accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * @param int $retries
     */
    public static function maxRetries($retries)
    {
        self::$maxRetries = abs($retries);
    }

    /**
     * 发起请求，返回响应结果
     * @param $url
     * @param array $data
     * @param string $method
     * @param bool $withToken
     * @param array $headers
     * @return mixed
     * @throws HttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function request($url, $data = [], $method = 'POST', $withToken = true, $headers = [])
    {
        if ($withToken) {
            $data = array_merge(
                $data,
                ['token' => $this->accessToken->getToken()]
            );
        }

        return HttpTrait::request($url, $data, $method, $headers);
    }

    /**
     * Check the array data errors, and Throw exception when the contents contains error.
     *
     * @param array $contents
     *
     * @throws \yii\web\HttpException
     */
    protected function checkAndThrow(array $contents)
    {
        if (isset($contents['resultCode']) && '0000' !== $contents['resultCode']) {
            if (empty($contents['resultMessage'])) {
                $contents['resultMessage'] = 'Unknown';
            }

            throw new HttpException($contents['resultMessage'], $contents['resultCode']);
        }
    }
}
