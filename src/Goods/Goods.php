<?php
/**
 * User: Ryan
 * Date: 2019/3/6
 * Time: 14:49
 */

namespace JDSDK\Goods;

use JDSDK\Core\AbstractAPI;

/**
 * 商品API接口
 * Class Goods
 * @package common\components\jd\Goods
 */
class Goods extends AbstractAPI
{
    const API_PAGENUM = 'https://bizapi.jd.com/api/product/XXXX';
    const API_SKU = 'https://bizapi.jd.com/api/product/XXXX';
    const API_DETAIL = 'https://bizapi.jd.com/api/product/XXXX';
    const API_SKUSTATE= 'https://bizapi.jd.com/api/product/XXXX ';
    const API_SKUIMAGE = 'https://bizapi.jd.com/api/product/XXXX';
    const API_COMMENTSUMMARYS = 'https://bizapi.jd.com/api/product/XXXX';
    const API_AREALIMIT = 'https://bizapi.jd.com/api/product/XXXX';
    const API_ISCOD = 'https://bizapi.jd.com/api/product/XXXX';
    const API_SKUGIFT = 'https://bizapi.jd.com/api/product/XXXX';
    const API_FREIGHT= 'https://bizapi.jd.com/api/order/XXXX';
    const API_SEARCH = 'https://bizapi.jd.com/api/search/XXXX';
    const API_CHECK = 'https://bizapi.jd.com/api/product/XXXX';
    const API_PROMISECALENDAR = 'https://bizapi.jd.com/api/order/XXXX';
    const API_YANBAOSKU = 'https://bizapi.jd.com/api/product/XXXX';
    const API_CATEGORYS = 'https://bizapi.jd.com/api/product/XXXX';
    const API_CATEGORY = 'https://bizapi.jd.com/api/product/XXXX';
    const API_SIMILARSKU = 'https://bizapi.jd.com/api/product/XXXX';

    /**
     * 获取商品池编号接口
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\HttpException
     */
    public function queryPageNum()
    {
        return $this->request(self::API_PAGENUM);
    }

    /**
     * 查询池内商品编号接口
     * @param $pageNum
     * @param $pageNo
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\HttpException
     */
    public function querySku($pageNum, $pageNo)
    {
        return $this->request(self::API_SKU, [
            'pageNum' => $pageNum,
            'pageNo' => $pageNo
        ]);
    }

    /**
     * 查询商品详细信息接口
     * @param $sku
     * @param string $queryExts
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\HttpException
     */
    public function queryDetail($sku, $queryExts = '')
    {
        return $this->request(self::API_DETAIL, [
            'sku' => $sku,
            'queryExts' => $queryExts
        ]);
    }

    /**
     * 查询商品上下架状态接口
     * @param $sku
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\HttpException
     */
    public function querySkuSate($sku)
    {
        return $this->request(self::API_SKUSTATE, [
            'sku' => $sku
        ]);
    }

    /**
     * 查询所有图片信息接口
     * @param $sku
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\HttpException
     */
    public function querySkuImage($sku)
    {
        return $this->request(self::API_SKUIMAGE, [
            'sku' => $sku
        ]);
    }

    /**
     * 查询商品好评度接口
     * @param $sku
     * @param string $queryExts
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\HttpException
     */
    public function queryCommentSummarys($sku, $queryExts = '')
    {
        return $this->request(self::API_COMMENTSUMMARYS, [
            'sku' => $sku,
            'queryExts' => $queryExts
        ]);
    }

    /**
     * 商品区域购买限制查询
     * @param $skuIds
     * @param $province
     * @param $city
     * @param $county
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\HttpException
     */
    public function checkAreaLimit($skuIds, $province, $city, $county)
    {
        return $this->request(self::API_AREALIMIT, [
            'skuIds' => $skuIds,
            'province' => $province,
            'city' => $city,
            'county' => $county
        ]);
    }

    /**
     * 商品区域是否支持货到付款
     * @param $skuIds
     * @param $province
     * @param $city
     * @param $county
     * @param $town
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\HttpException
     */
    public function isCod($skuIds, $province, $city, $county, $town)
    {
        return $this->request(self::API_ISCOD, [
            'skuIds' => $skuIds,
            'province' => $province,
            'city' => $city,
            'county' => $county,
            'town' => $town
        ]);
    }

    /**
     * 查询赠品信息接口
     * @param $skuId
     * @param $province
     * @param $city
     * @param $county
     * @param $town
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\HttpException
     */
    public function querySkuGift($skuId, $province, $city, $county, $town)
    {
        return $this->request(self::API_SKUGIFT, [
            'skuId' => $skuId,
            'province' => $province,
            'city' => $city,
            'county' => $county,
            'town' => $town
        ]);
    }

    /**
     * 商品搜索接口
     * @param string $keyword
     * @param string $catId
     * @param int $pageIndex
     * @param int $pageSize
     * @param string $min
     * @param string $max
     * @param string $brands
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\HttpException
     */
    public function search($keyword = '', $catId = '', $pageIndex = 0, $pageSize = 0, $min = '', $max = '', $brands = '')
    {
        $url = $this->buildApiUrl(self::API_SEARCH, compact(
            'keyword', 'catId', 'pageIndex', 'pageSize', 'min', 'max', 'brands'
        ));

        return $this->request($url);
    }

    /**
     * 商品可售验证接口
     * @param $skuIds
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\HttpException
     */
    public function check($skuIds)
    {
        $url = $this->buildApiUrl(self::API_CHECK, compact('skuIds'));

        return $this->request($url);
    }

    /**
     * 获取京东预约日历
     * @param $province
     * @param $city
     * @param $county
     * @param $town
     * @param $paymentType
     * @param $sku
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\HttpException
     */
    public function promiseCalendar($province, $city, $county, $town, $paymentType, $sku)
    {
        return $this->request(self::API_PROMISECALENDAR, [
            'province' => $province,
            'city' => $city,
            'county' => $county,
            'town' => $town,
            'paymentType' => $paymentType,
            'sku' => $sku
        ]);
    }

    /**
     * 查询商品延保接口
     * @param $skuIds
     * @param $province
     * @param $city
     * @param $county
     * @param $town
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\HttpException
     */
    public function queryYanbaoSku($skuIds, $province, $city, $county, $town)
    {
        $url = $this->buildApiUrl(self::API_YANBAOSKU, compact(
            'skuIds', 'province', 'city', 'county', 'town'
        ));

        return $this->request($url);
    }

    /**
     * 查询分类列表信息接口
     * @param $pageNo
     * @param $pageSize
     * @param $parentId
     * @param $catClass
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\HttpException
     */
    public function queryCategorys($pageNo, $pageSize, $parentId, $catClass)
    {
        $url = $this->buildApiUrl(self::API_CATEGORYS, compact(
            'pageNo', 'pageSize', 'parentId', 'catClass'
        ));

        return $this->request($url);
    }

    /**
     * 查询分类信息接口
     * @param $cid
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\HttpException
     */
    public function queryCategory($cid)
    {
        $url = $this->buildApiUrl(self::API_CATEGORY, compact('cid'));

        return $this->request($url);
    }

    /**
     * 同类商品查询接口
     * @param $skuId
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\HttpException
     */
    public function querySimilarSku($skuId)
    {
        $url = $this->buildApiUrl(self::API_SIMILARSKU, compact('skuId'));

        return $this->request($url);
    }
}