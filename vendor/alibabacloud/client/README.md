简体中文 | [English](README-EN.md)


<p align="center">
<a href=" https://www.alibabacloud.com"><img src="https://aliyunsdk-pages.alicdn.com/icons/Aliyun.svg"></a>
</p>

<h1 align="center">Alibaba Cloud Client for PHP</h1>

<p align="center">
<a href="https://packagist.org/packages/alibabacloud/client"><img src="https://poser.pugx.org/alibabacloud/client/v/stable" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/alibabacloud/client"><img src="https://poser.pugx.org/alibabacloud/client/v/unstable" alt="Latest Unstable Version"></a>
<a href="https://packagist.org/packages/alibabacloud/client"><img src="https://poser.pugx.org/alibabacloud/client/composerlock" alt="composer.lock"></a>
<a href="https://packagist.org/packages/alibabacloud/client"><img src="https://poser.pugx.org/alibabacloud/client/downloads" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/alibabacloud/client"><img src="https://poser.pugx.org/alibabacloud/client/license" alt="License"></a>
<br/>
<a href="https://codecov.io/gh/aliyun/openapi-sdk-php-client"><img src="https://codecov.io/gh/aliyun/openapi-sdk-php-client/branch/master/graph/badge.svg" alt="codecov"></a>
<a href="https://scrutinizer-ci.com/g/aliyun/openapi-sdk-php-client"><img src="https://scrutinizer-ci.com/g/aliyun/openapi-sdk-php-client/badges/quality-score.png" alt="Scrutinizer Code Quality"></a>
<a href="https://travis-ci.org/aliyun/openapi-sdk-php-client"><img src="https://travis-ci.org/aliyun/openapi-sdk-php-client.svg?branch=master" alt="Travis Build Status"></a>
<a href="https://ci.appveyor.com/project/aliyun/openapi-sdk-php-client/branch/master"><img src="https://ci.appveyor.com/api/projects/status/699v083woth7mj85/branch/master?svg=true" alt="Appveyor Build Status"></a>
<a href="https://scrutinizer-ci.com/code-intelligence"><img src="https://scrutinizer-ci.com/g/aliyun/openapi-sdk-php-client/badges/code-intelligence.svg" alt="Code Intelligence Status"></a>
</p>


Alibaba Cloud Client for PHP 是帮助 PHP 开发者管理凭据、发送请求的客户端工具，[Alibaba Cloud SDK for PHP][SDK] 由本工具提供底层支持。


## 在线示例
[API Explorer](https://api.aliyun.com) 提供在线调用阿里云产品，并动态生成 SDK 代码和快速检索接口等能力，能显著降低使用云 API 的难度。


## 先决条件
您的系统需要满足[先决条件](docs/zh/0-Prerequisites.md)，包括 PHP> = 5.5。 我们强烈建议使用cURL扩展，并使用TLS后端编译cURL 7.16.2+。


## 安装依赖
如果已在系统上[全局安装 Composer](https://getcomposer.org/doc/00-intro.md#globally)，请直接在项目目录中运行以下内容来安装 Alibaba Cloud Client for PHP 作为依赖项：
```
composer require alibabacloud/client
```
> 一些用户可能由于网络问题无法安装，可以尝试切换 Composer 镜像地址。

请看[安装](docs/zh/1-Installation.md)有关通过 Composer 和其他方式安装的详细信息。


## 快速使用
在您开始之前，您需要注册阿里云帐户并获取您的[凭证](https://usercenter.console.aliyun.com/#/manage/ak)。

### 创建客户端
```php
<?php

use AlibabaCloud\Client\AlibabaCloud;

AlibabaCloud::accessKeyClient('accessKeyId', 'accessKeySecret')->asDefaultClient();
```

### ROA 请求
```php
<?php

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

try {
    $result = AlibabaCloud::roa()
                          ->regionId('cn-hangzhou') // 指定请求的区域，不指定则使用客户端区域、默认区域
                          ->product('CS') // 指定产品
                          ->version('2015-12-15') // 指定产品版本
                          ->action('DescribeClusterServices') // 指定产品接口
                          ->serviceCode('cs') // 设置 ServiceCode 以备寻址，非必须
                          ->endpointType('openAPI') // 设置类型，非必须
                          ->method('GET') // 指定请求方式
                          ->host('cs.aliyun.com') // 指定域名则不会寻址，如认证方式为 Bearer Token 的服务则需要指定
                          ->pathPattern('/clusters/[ClusterId]/services') // 指定ROA风格路径规则
                          ->withClusterId('123456') // 为路径中参数赋值，方法名：with + 参数
                          ->request(); // 发起请求并返回结果对象，请求需要放在设置的最后面

    print_r($result->toArray());
    
} catch (ClientException $exception) {
    print_r($exception->getErrorMessage());
} catch (ServerException $exception) {
    print_r($exception->getErrorMessage());
}
```

### RPC 请求
```php
<?php

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

try {
    $result = AlibabaCloud::rpc()
                          ->product('Cdn')
                          ->version('2014-11-11')
                          ->action('DescribeCdnService')
                          ->method('POST')
                          ->request();

    print_r($result->toArray());

} catch (ClientException $exception) {
    print_r($exception->getErrorMessage());
} catch (ServerException $exception) {
    print_r($exception->getErrorMessage());
}
```


## 文档
* [先决条件](docs/zh/0-Prerequisites.md)
* [安装](docs/zh/1-Installation.md)
* [客户端](docs/zh/2-Client.md)
* [请求](docs/zh/3-Request.md)
* [结果](docs/zh/4-Result.md)
* [区域](docs/zh/5-Region.md)
* [域名](docs/zh/6-Host.md)
* [SSL 验证](docs/zh/7-Verify.md)
* [调试](docs/zh/8-Debug.md)
* [日志](docs/zh/9-Log.md)
* [测试](docs/zh/10-Test.md)


## 问题
[提交 Issue](https://github.com/aliyun/openapi-sdk-php-client/issues/new/choose)，不符合指南的问题可能会立即关闭。


## 发行说明
每个版本的详细更改记录在[发行说明](CHANGELOG.md)中。


## 贡献
提交 Pull Request 之前请阅读[贡献指南](CONTRIBUTING.md)。


## 相关
* [阿里云服务 Regions & Endpoints][endpoints]
* [OpenAPI Explorer][open-api]
* [Packagist][packagist]
* [Composer][composer]
* [Guzzle中文文档][guzzle-docs]
* [最新源码][latest-release]


## 许可证
[Apache-2.0](LICENSE.md)

版权所有 1999-2019 阿里巴巴集团


[SDK]: https://github.com/aliyun/openapi-sdk-php/blob/master/README.md
[open-api]: https://api.aliyun.com
[latest-release]: https://github.com/aliyun/openapi-sdk-php-client
[guzzle-docs]: https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html
[composer]: https://getcomposer.org
[packagist]: https://packagist.org/packages/alibabacloud/sdk
[home]: https://home.console.aliyun.com
[aliyun]: https://www.aliyun.com
[regions]: https://help.aliyun.com/document_detail/40654.html
[endpoints]: https://developer.aliyun.com/endpoints
[cURL]: http://php.net/manual/zh/book.curl.php
[OPCache]: http://php.net/manual/zh/book.opcache.php
[xdebug]: http://xdebug.org
[OpenSSL]: http://php.net/manual/zh/book.openssl.php
[client]: https://github.com/aliyun/openapi-sdk-php-client
