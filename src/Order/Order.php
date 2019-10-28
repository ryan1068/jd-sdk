<?php
/**
 * User: Ryan
 * Date: 2019/3/7
 * Time: 10:06
 */

namespace JDSDK\Order;

use JDSDK\Core\AbstractAPI;

/**
 * 订单API接口
 * Class Order
 * @package common\components\jd\Order
 */
class Order extends AbstractAPI
{
    const API_SUBMITORDER = 'https://bizapi.jd.com/api/order/XXXX';
    const API_CONFIRMORDER = 'https://bizapi.jd.com/api/order/XXXX';
    const API_DOPAY = 'https://bizapi.jd.com/api/order/XXXX';
    const API_CANCEL = 'https://bizapi.jd.com/api/order/XXXX';
    const API_SELECTJDORDER = 'https://bizapi.jd.com/api/order/XXXX';
    const API_SELECTJDORDERIDBYTHIRDORDER = 'https://bizapi.jd.com/api/order/XXXX';
    const API_ORDERTRACK = 'https://bizapi.jd.com/api/order/XXXX';

    //对账API接口
    const API_CHECKNEWORDER = 'https://bizapi.jd.com/api/checkOrder/XXXX';
    const API_CHECKDLOKORDER = 'https://bizapi.jd.com/api/checkOrder/XXXX';
    const API_CHECKREFUSEORDER = 'https://bizapi.jd.com/api/checkOrder/XXXX';

    /**
     * 统一下单接口
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\HttpException
     */
    public function submitOrder()
    {
        return $this->request(self::API_SUBMITORDER);
    }

    /**
     * 确认预占库存订单接口
     * @param string $jdOrderId
     * @param string $companyPayMoney
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\HttpException
     */
    public function confirmOrder($jdOrderId, $companyPayMoney = '')
    {
        return $this->request(self::API_CONFIRMORDER, compact(
            'jdOrderId', 'companyPayMoney'
        ));
    }

    /**
     * 发起支付接口
     * @param string $jdOrderId
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\HttpException
     */
    public function doPay($jdOrderId)
    {
        return $this->request(self::API_DOPAY, compact('jdOrderId'));
    }

    /**
     * 取消未确认订单接口
     * @param string $jdOrderId
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\HttpException
     */
    public function cancel($jdOrderId)
    {
        return $this->request(self::API_CANCEL, compact('jdOrderId'));
    }

    /**
     * 查询京东订单信息接口
     * @param integer $jdOrderId
     * @param string $queryExts
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\HttpException
     */
    public function selectJdOrder($jdOrderId, $queryExts = '')
    {
        return $this->request(self::API_SELECTJDORDER, compact(
            'jdOrderId', 'queryExts'
        ));
    }

    /**
     * 订单反查接口
     * @param string $thirdOrder
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\HttpException
     */
    public function selectJdOrderIdByThirdOrder($thirdOrder)
    {
        return $this->request(self::API_SELECTJDORDERIDBYTHIRDORDER, compact('thirdOrder'));
    }

    /**
     * 查询配送信息接口
     * @param string $jdOrderId
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\HttpException
     */
    public function orderTrack($jdOrderId)
    {
        return $this->request(self::API_ORDERTRACK, compact('jdOrderId'));
    }

    /**
     * 新建订单查询接口
     * @param string $date
     * @param string $pageNo
     * @param string $pageSize
     * @param string $jdOrderIdIndex
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\HttpException
     */
    public function checkNewOrder($date, $pageNo = '', $pageSize = '', $jdOrderIdIndex = '')
    {
        return $this->request(self::API_CHECKNEWORDER, compact(
            'date', 'pageNo', 'pageSize', 'jdOrderIdIndex'
        ));
    }

    /**
     * 获取妥投订单接口
     * @param string $date
     * @param string $pageNo
     * @param string $pageSize
     * @param string $jdOrderIdIndex
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\HttpException
     */
    public function checkDlokOrder($date, $pageNo = '', $pageSize = '', $jdOrderIdIndex = '')
    {
        return $this->request(self::API_CHECKDLOKORDER, compact(
            'date', 'pageNo', 'pageSize', 'jdOrderIdIndex'
        ));
    }

    /**
     * 获取拒收订单接口
     * @param string $date
     * @param string $pageNo
     * @param string $pageSize
     * @param string $jdOrderIdIndex
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\HttpException
     */
    public function checkRefuseOrder($date, $pageNo = '', $pageSize = '', $jdOrderIdIndex = '')
    {
        return $this->request(self::API_CHECKREFUSEORDER, compact(
            'date', 'pageNo', 'pageSize', 'jdOrderIdIndex'
        ));
    }
}