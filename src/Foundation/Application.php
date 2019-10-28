<?php
/**
 * User: Ryan
 * Date: 2019/3/6
 * Time: 14:49
 */

namespace JDSDK\Foundation;

use JDSDK\Core\AbstractAPI;
use JDSDK\Core\AccessToken;
use Doctrine\Common\Cache\FilesystemCache;
use Pimple\Container;
use Psr\SimpleCache\CacheInterface;

/**
 * Class Application.
 *
 * @property \JDSDK\Core\AccessToken        $access_token
 * @property \JDSDK\Goods\Goods             $goods
 * @property \JDSDK\Order\Order             $order
 *
 */
class Application extends Container
{
    /**
     * Service Providers.
     *
     * @var array
     */
    protected $providers = [
        ServiceProviders\GoodsServiceProvider::class,
        ServiceProviders\OrderServiceProvider::class,
    ];

    /**
     * Application constructor.
     *
     * @param array $config
     */
    public function __construct($config)
    {
        parent::__construct();

        $this['config'] = function () use ($config) {
            return new Config($config);
        };

        $this->registerProviders();
        $this->registerBase();

        AbstractAPI::maxRetries($this['config']->get('max_retries', 2));
        $this->logConfiguration($config);
    }

    /**
     * Log configuration.
     *
     * @param array $config
     */
    public function logConfiguration($config)
    {
        $config = new Config($config);

        $keys = ['clientId', 'clientSecret', 'username', 'password'];
        foreach ($keys as $key) {
            if (!$config->has($key)) {
                $config->forget($key);
            }
        }

        \Yii::info($config->toArray(), 'Jd config');
    }

    /**
     * Add a provider.
     *
     * @param string $provider
     *
     * @return Application
     */
    public function addProvider($provider)
    {
        array_push($this->providers, $provider);

        return $this;
    }

    /**
     * Set providers.
     *
     * @param array $providers
     */
    public function setProviders(array $providers)
    {
        $this->providers = [];

        foreach ($providers as $provider) {
            $this->addProvider($provider);
        }
    }

    /**
     * Return all providers.
     *
     * @return array
     */
    public function getProviders()
    {
        return $this->providers;
    }

    /**
     * Magic get access.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function __get($id)
    {
        return $this->offsetGet($id);
    }

    /**
     * Magic set access.
     *
     * @param string $id
     * @param mixed  $value
     */
    public function __set($id, $value)
    {
        $this->offsetSet($id, $value);
    }

    /**
     * Register providers.
     */
    private function registerProviders()
    {
        foreach ($this->providers as $provider) {
            $this->register(new $provider());
        }
    }

    /**
     * Register basic providers.
     */
    private function registerBase()
    {
        if (!empty($this['config']['cache']) && $this['config']['cache'] instanceof CacheInterface) {
            $this['cache'] = $this['config']['cache'];
        } else {
            $this['cache'] = function () {
                return new FilesystemCache(sys_get_temp_dir());
            };
        }

        $this['access_token'] = function () {
            return new AccessToken(
                $this['config']['clientId'],
                $this['config']['clientSecret'],
                $this['config']['username'],
                $this['config']['password'],
                $this['cache']
            );
        };
    }

    /**
     * Magic call，可直接调用组件方法
     *
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function __call($method, $args)
    {
        if (is_callable([$this['access_token'], $method])) {
            return call_user_func_array([$this['access_token'], $method], $args);
        }

        throw new \Exception("Call to undefined method {$method}()");
    }
}
