<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyWeChat\MicroMerchant\Base;

use EasyWeChat\MicroMerchant\Kernel\BaseClient;

/**
 * Class Client.
 *
 * @author   liuml  <liumenglei0211@163.com>
 * @DateTime 2019-05-30  14:19
 */
class Client extends BaseClient
{
    /**
     * apply to settle in to become a small micro merchant.
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\MicroMerchant\Kernel\Exceptions\EncryptException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function submitApplication(array $params)
    {
        $params = $this->processParams(array_merge($params, [
            'version' => '3.0',
            'cert_sn' => '',
            'sign_type' => 'HMAC-SHA256',
            'nonce_str' => uniqid('micro'),
        ]));

        return $this->safeRequest('applyment/micro/submit', $params);
    }

    /**
     * query application status.
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getStatus(string $applymentId, string $businessCode = '')
    {
        if (!empty($applymentId)) {
            $params = [
                'applyment_id' => $applymentId,
            ];
        } else {
            $params = [
                'business_code' => $businessCode,
            ];
        }

        $params = array_merge($params, [
            'version' => '1.0',
            'sign_type' => 'HMAC-SHA256',
            'nonce_str' => uniqid('micro'),
        ]);

        return $this->safeRequest('applyment/micro/getstate', $params);
    }

    /**
     * merchant upgrade api.
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\MicroMerchant\Kernel\Exceptions\EncryptException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function upgrade(array $params)
    {
        $params['sub_mch_id'] = $params['sub_mch_id'] ?? $this->app['config']->sub_mch_id;
        $params = $this->processParams(array_merge($params, [
            'version' => '1.0',
            'cert_sn' => '',
            'sign_type' => 'HMAC-SHA256',
            'nonce_str' => uniqid('micro'),
        ]));

        return $this->safeRequest('applyment/micro/submitupgrade', $params);
    }

    /**
     * get upgrade status.
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getUpgradeStatus(string $subMchId = '')
    {
        return $this->safeRequest('applyment/micro/getupgradestate', [
            'version' => '1.0',
            'sign_type' => 'HMAC-SHA256',
            'sub_mch_id' => $subMchId ?: $this->app['config']->sub_mch_id,
            'nonce_str' => uniqid('micro'),
        ]);
    }
}
