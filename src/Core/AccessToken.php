<?php
/**
 * User: Ryan
 * Date: 2019/3/6
 * Time: 14:49
 */

namespace JDSDK\Core;

use JDSDK\Common\Traits\HttpTrait;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\RedisCache;
use yii\web\HttpException;

/**
 * Class AccessToken.
 */
class AccessToken
{
    use HttpTrait;

    /**
     * 对接账号
     *
     * @var string
     */
    protected $clientId;

    /**
     * 对接账号的密码
     *
     * @var string
     */
    protected $clientSecret;

    /**
     * 京东的用户名
     *
     * @var string
     */
    protected $username;

    /**
     * 京东的用户密码
     *
     * @var string
     */
    protected $password;

    /**
     * 授权类型 该值固定为 access_token
     *
     * @var string
     */
    protected $grantType = 'access_token';

    /**
     * 申请权限 （目前推荐为空。为以后扩展用）
     *
     * @var string
     */
    protected $scope = '';

    /**
     * Cache.
     *
     * @var Cache
     */
    protected $cache;

    /**
     * Cache Key.
     *
     * @var string
     */
    protected $cacheKey;

    /**
     * Response Json key name.
     *
     * @var string
     */
    protected $tokenJsonKey = 'access_token';

    /**
     * Cache key prefix.
     *
     * @var string
     */
    protected $cachePrefix = 'jd.access_token.';

    // API
    const API_ACCESSTOKEN = 'https://bizapi.jd.com/oauth2/accessToken';
    const API_REFRESHTOKEN = 'https://bizapi.jd.com/oauth2/refreshToken';

    /**
     * AccessToken constructor.
     * @param $clientId
     * @param $clientSecret
     * @param $username
     * @param $password
     * @param Cache|null $cache
     */
    public function __construct($clientId, $clientSecret, $username, $password, Cache $cache = null)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->username = $username;
        $this->password = $password;
        $this->cache = $cache;
    }

    /**
     * Get token.
     * @param bool $forceRefresh
     * @return mixed
     * @throws HttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function getToken($forceRefresh = false)
    {
        $cacheKey = $this->getCacheKey();
        $cached = $this->getCache()->fetch($cacheKey);

        if ($forceRefresh || empty($cached)) {
            $token = $this->getTokenFromServer($forceRefresh);

            $this->setToken($token['result'][$this->tokenJsonKey], $token['result']['expires_in']);

            $refreshToken = $this->getCache()->fetch($this->getRefreshTokenCacheKey());
            if ($forceRefresh || !$refreshToken) {
                $this->setRefreshToken($token);
            }

            return $token['result'][$this->tokenJsonKey];
        }

        return $cached;
    }

    /**
     * 设置自定义 token.
     *
     * @param string $token
     * @param int    $expires
     *
     * @return $this
     */
    public function setToken($token, $expires = 86400)
    {
        $this->getCache()->save($this->getCacheKey(), $token, $expires - 1500);

        return $this;
    }

    /**
     * 设置自定义 refresh token.
     *
     * @param $token
     *
     * @return $this
     */
    public function setRefreshToken($token)
    {
        $refreshToken = $token['result']['refresh_token'];
        $currentTime = $token['result']['time'];
        $expiresTime = $token['result']['refresh_token_expires'];
        $expiresIn = ($expiresTime - $currentTime) - 1500;

        $this->getCache()->save($this->getRefreshTokenCacheKey(), $refreshToken, $expiresIn);

        return $this;
    }

    /**
     * Return the client id.
     *
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Return the client secret.
     *
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * Set cache instance.
     *
     * @param \Doctrine\Common\Cache\Cache $cache
     *
     * @return AccessToken
     */
    public function setCache(Cache $cache)
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * Return the cache manager.
     *
     * @return \Doctrine\Common\Cache\Cache
     */
    public function getCache()
    {
        if (!$this->cache) {
            $this->cache = new RedisCache();
            $this->cache->setRedis(\Yii::$app->redis);
        }
        return $this->cache;
    }

    /**
     * 生成签名
     * 生成规则如下：
     *   1.按照以下顺序将字符串拼接起来
     *   client_secret+timestamp+client_id+username+password+grant_type+scope+client_secret
     *   其中 client_secret 的值是京东分配的, username 使用原文，password 需要 md5 加密后的,当 username 为中文时，需确定自带 MD5 程序是否支持
     *   2.将上述拼接的字符串使用 MD5 加密，加密后的值再转为大写
     *
     * @throws \yii\base\InvalidConfigException
     */
    private function generateSign()
    {
        return strtoupper(md5($this->clientSecret
            . \Yii::$app->formatter->asDatetime(time())
            . $this->clientId
            . $this->username
            . md5($this->password)
            . $this->grantType
            . $this->scope
            . $this->clientSecret));
    }

    /**
     * 获取请求url
     * https://bizapi.jd.com/oauth2/accessToken?username=测试账户&password=e10adc3949ba59abbe56&client_id=SzaYHDblfgYWl&clientSecret=5pNYsiQ4Ues&grant_type=access_token&timestamp=2018-04-11 17:14:00&sign=9ECBBE047C7328422878430B40FFF64A
     *
     * 输入参数：
     * 1.grant_type = access_token
     * 2.client_id = yourclientid
     * 3.username = yourpin
     * 4.password = yourpassword
     * 5.timestamp = 2014-01-01 01:01:01
     * 6.scope=
     * client_secret = yourclientsecret
     * 把所有参数按照顺序拼接起来，结果如下：
     * yourclientsecret2014-01-01 01:01:01yourclientidyourpinyourpasswordaccess_tokenyourclientsecret sign 值为上述字符串进行 MD5 加密后转为大写，结果如下：
     * 7.sign=00C29BAAB23BBCE20C9BBD9C180E8330
     * 具体生成的访问 url 为：
     * http://bizapi.jd.com/oauth2/accessToken?grant_type=access_token&client_id=yourclientid&scope=&username=yourpin&password=yourpassword&timestamp=2014-01-01
     * 01:01:01&sign=00C29BAAB23BBCE20C9BBD9C180E8330 注：如果 username 是中文的话，在 url 中，需要进行 UrlEncode.encode(“中文”,”utf-8”);
     *
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getApiUrl()
    {
        return $this->buildApiUrl(self::API_ACCESSTOKEN, [
            'grant_type' => $this->grantType,
            'client_id' => $this->clientId,
            'username' => $this->username,
            'password' => md5($this->password),
            'timestamp' => \Yii::$app->formatter->asDatetime(time()),
            'scope' => $this->scope,
            'sign' => $this->generateSign(),
        ]);
    }

    /**
     * Get the access token from server.
     * @param bool $forceRefresh
     * @return array|mixed
     * @throws HttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function getTokenFromServer($forceRefresh = false)
    {
        $refreshToken = $this->getCache()->fetch($this->getRefreshTokenCacheKey());
        if ($refreshToken && !$forceRefresh) {
            $token = $this->refreshToken($refreshToken);
        } else {
            $token = $this->request($this->getApiUrl());
        }

        if (empty($token['result'][$this->tokenJsonKey])) {
            throw new HttpException('Request AccessToken fail');
        }

        return $token;
    }

    /**
     * refresh token
     * @param string $refreshToken
     * @return mixed
     */
    public function refreshToken($refreshToken)
    {
        return $this->request(self::API_REFRESHTOKEN, [
            'refresh_token' => $refreshToken,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret
        ]);
    }

    /**
     * Set the access token prefix.
     *
     * @param string $prefix
     *
     * @return $this
     */
    public function setPrefix($prefix)
    {
        $this->cachePrefix = $prefix;

        return $this;
    }

    /**
     * Set access token cache key.
     *
     * @param string $cacheKey
     *
     * @return $this
     */
    public function setCacheKey($cacheKey)
    {
        $this->cacheKey = $cacheKey;

        return $this;
    }

    /**
     * Get access token cache key.
     *
     * @return string $this->cacheKey
     */
    public function getCacheKey()
    {
        if (is_null($this->cacheKey)) {
            return $this->cachePrefix.$this->clientId;
        }

        return $this->cacheKey;
    }

    /**
     * Get refresh token cache key.
     * @return string
     */
    public function getRefreshTokenCacheKey()
    {
        return $this->cachePrefix.$this->clientId.'.refresh_token';
    }
}
