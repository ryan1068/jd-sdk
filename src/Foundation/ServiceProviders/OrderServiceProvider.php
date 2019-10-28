<?php
/**
 * User: Ryan
 * Date: 2019/3/6
 * Time: 14:49
 */

namespace JDSDK\Foundation\ServiceProviders;

use JDSDK\Order\Order;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class OrderServiceProvider
 * @package common\components\jd\Foundation\ServiceProviders
 */
class OrderServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        $pimple['order'] = function ($pimple) {
            return new Order($pimple['access_token']);
        };
    }
}
