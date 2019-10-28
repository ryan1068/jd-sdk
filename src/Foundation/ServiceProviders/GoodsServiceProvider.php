<?php
/**
 * User: Ryan
 * Date: 2019/3/6
 * Time: 14:49
 */

namespace JDSDK\Foundation\ServiceProviders;

use JDSDK\Goods\Goods;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class GoodsServiceProvider.
 */
class GoodsServiceProvider implements ServiceProviderInterface
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
        $pimple['goods'] = function ($pimple) {
            return new Goods($pimple['access_token']);
        };
    }
}
