腾讯云短信 PHP SDK
===

## 腾讯短信服务

目前`腾讯云短信`为客户提供`国内短信`、`国内语音`和`海外短信`三大服务，腾讯云短信SDK支持以下操作：

### 国内短信

国内短信支持操作：

- 单发短信
- 指定模板单发短信
- 群发短信
- 指定模板群发短信
- 拉取短信回执和短信回复状态

> `Note` 短信拉取功能需要联系腾讯云短信技术支持(QQ:3012203387)开通权限，量大客户可以使用此功能批量拉取，其他客户不建议使用。

### 海外短信

海外短信支持操作：

- 单发短信
- 指定模板单发短信
- 群发短信
- 指定模板群发短信
- 拉取短信回执和短信回复状态

> `Note` 海外短信和国内短信使用同一接口，只需替换相应的国家码与手机号码，每次请求群发接口手机号码需全部为国内或者海外手机号码。

### 语音通知

语音通知支持操作：

- 发送语音验证码
- 发送语音通知
- 上传语音文件
- 按语音文件fid发送语音通知
- 指定模板发送语音通知类

## 开发

### 准备

在开始开发云短信应用之前，需要准备如下信息:

- [x] 获取SDK AppID和AppKey

云短信应用SDK `AppID`和`AppKey`可在[短信控制台](https://console.cloud.tencent.com/sms)的应用信息里获取，如您尚未添加应用，请到[短信控制台](https://console.cloud.tencent.com/sms)中添加应用。

- [x] 申请签名

一个完整的短信由短信`签名`和短信正文内容两部分组成，短信`签名`须申请和审核，`签名`可在[短信控制台](https://console.cloud.tencent.com/sms)的相应服务模块`内容配置`中进行申请。

- [x] 申请模板

同样短信或语音正文内容`模板`须申请和审核，`模板`可在[短信控制台](https://console.cloud.tencent.com/sms)的相应服务模块`内容配置`中进行申请。

## 安装

### Composer

qcloudsms_php采用composer进行安装，要使用qcloudsms功能，只需要在composer.json中添加如下依赖：

```json
{
  "require": {
    "qcloudsms/qcloudsms_php": "0.1.*"
  }
}
```

> `Note` Composer的使用可以参考demo目录下面的示例。

### 手动

1. 手动下载或clone最新版本qcloudsms_php代码
2. 把qcloudsms_php放入项目目录
3. `require` qcloudsms_php src目录下面的index.php，即可使用，如把qcloudsms放在当前目录下，只需要:

```php
require __DIR__ . "/qcloudsms_php/src/index.php";
```

## 用法

若您对接口存在疑问，可以查阅 [API文档](https://cloud.tencent.com/document/product/382/13297) 、[SDK文档](https://qcloudsms.github.io/qcloudsms_php/) 和 [错误码](https://cloud.tencent.com/document/product/382/3771)。

- **准备必要参数**

```php
// 短信应用SDK AppID
$appid = 1400009099; // 1400开头

// 短信应用SDK AppKey
$appkey = "9ff91d87c2cd7cd0ea762f141975d1df37481d48700d70ac37470aefc60f9bad";

// 需要发送短信的手机号码
$phoneNumbers = ["21212313123", "12345678902", "12345678903"];

// 短信模板ID，需要在短信应用中申请
$templateId = 7839;  // NOTE: 这里的模板ID`7839`只是一个示例，真实的模板ID需要在短信控制台中申请

$smsSign = "腾讯云"; // NOTE: 这里的签名只是示例，请使用真实的已申请的签名，签名参数使用的是`签名内容`，而不是`签名ID`
```

- **单发短信**

```php
use Qcloud\Sms\SmsSingleSender;

try {
    $ssender = new SmsSingleSender($appid, $appkey);
    $result = $ssender->send(0, "86", $phoneNumbers[0],
        "【腾讯云】您的验证码是: 5678", "", "");
    $rsp = json_decode($result);
    echo $result;
} catch(\Exception $e) {
    echo var_dump($e);
}
```

> `Note` 如需发送海外短信，同样可以使用此接口，只需将国家码 `86` 改写成对应国家码号。
> `Note` 无论单发/群发短信还是指定模板ID单发/群发短信都需要从控制台中申请模板并且模板已经审核通过，才可能下发成功，否则返回失败。

- **指定模板ID单发短信**

```php
use Qcloud\Sms\SmsSingleSender;

try {
    $ssender = new SmsSingleSender($appid, $appkey);
    $params = ["5678"];
    $result = $ssender->sendWithParam("86", $phoneNumbers[0], $templateId,
        $params, $smsSign, "", "");  // 签名参数未提供或者为空时，会使用默认签名发送短信
    $rsp = json_decode($result);
    echo $result;
} catch(\Exception $e) {
    echo var_dump($e);
}
```

> `Note` 无论单发/群发短信还是指定模板ID单发/群发短信都需要从控制台中申请模板并且模板已经审核通过，才可能下发成功，否则返回失败。

- **群发**

```php
use Qcloud\Sms\SmsMultiSender;

try {
    $msender = new SmsMultiSender($appid, $appkey);
    $result = $msender->send(0, "86", $phoneNumbers,
        "【腾讯云】您的验证码是: 5678", "", "");
    $rsp = json_decode($result);
    echo $result;
} catch(\Exception $e) {
    echo var_dump($e);
}
```

> `Note` 无论单发/群发短信还是指定模板ID单发/群发短信都需要从控制台中申请模板并且模板已经审核通过，才可能下发成功，否则返回失败。

- **指定模板ID群发**

```php
use Qcloud\Sms\SmsMultiSender;

try {
    $msender = new SmsMultiSender($appid, $appkey);
    $params = ["5678"];
    $result = $msender->sendWithParam("86", $phoneNumbers,
        $templateId, $params, $smsSign, "", "");  // 签名参数未提供或者为空时，会使用默认签名发送短信
    $rsp = json_decode($result);
    echo $result;
} catch(\Exception $e) {
    echo var_dump($e);
}
```

> `Note` 群发一次请求最多支持200个号码，如有对号码数量有特殊需求请联系腾讯云短信技术支持(QQ:3012203387)。
> `Note` 无论单发/群发短信还是指定模板ID单发/群发短信都需要从控制台中申请模板并且模板已经审核通过，才可能下发成功，否则返回失败。

- **发送语音验证码**

```php
use Qcloud\Sms\SmsVoiceVerifyCodeSender;

try {
    $vvcsender = new SmsVoiceVerifyCodeSender($appid, $appkey);
    $result = $vvcsender->send("86", $phoneNumbers[0], "5678", 2, "");
    $rsp = json_decode($result);
    echo $result;
} catch (\Exception $e) {
    echo var_dump($e);
}
```

> `Note` 语音验证码发送只需提供验证码数字，例如当msg=“5678”时，您收到的语音通知为“您的语音验证码是5678”，如需自定义内容，可以使用语音通知。

- **发送语音通知**

```php
use Qcloud\Sms\SmsVoicePromptSender;

try {
    $vpsender = new SmsVoicePromptSender($appid, $appkey);
    $result = $vpsender->send("86", $phoneNumbers[0], 2, "5678", "");
    $rsp = json_decode($result);
    echo $result;
} catch (\Exception $e) {
    echo var_dump($e);
}
```

- **拉取短信回执以及回复**

```php
use Qcloud\Sms\SmsStatusPuller;

try {
    $sspuller = new SmsStatusPuller($appid, $appkey);

    // 拉取短信回执
    $callbackResult = $spuller->pullCallback(10);
    $callbackRsp = json_decode($callbackResult);
    echo $callbackResult;

    // 拉取回复
    $replyResult = $spuller->pullReply(10);
    $replyRsp = json_decode($replyResult);
    echo $replyResult;
} catch (\Exception $e) {
    echo var_dump($e);
}
```

> `Note` 短信拉取功能需要联系腾讯云短信技术支持(QQ:3012203387)，量大客户可以使用此功能批量拉取，其他客户不建议使用。

- **拉取单个手机短信状态**

```php
use Qcloud\Sms\SmsMobileStatusPuller;

try {
    $beginTime = 1511125600;  // 开始时间(unix timestamp)
    $endTime = 1511841600;    // 结束时间(unix timestamp)
    $maxNum = 10;             // 单次拉取最大量
    $mspuller = new SmsMobileStatusPuller($appid, $appkey);

    // 拉取短信回执
    $callbackResult = $mspuller->pullCallback("86", $phoneNumbers[0],
        $beginTime, $endTime, $maxNum);
    $callbackRsp = json_decode($callbackResult);
    echo $callbackResult;

    // 拉取回复
    $replyResult = $mspuller->pullReply("86", $phoneNumbers[0],
        $beginTime, $endTime, $maxNum);
    $replyRsp = json_decode($replyResult);
    echo $replyResult;
} catch (\Exception $e) {
    echo var_dump($e);
}
```

> `Note` 短信拉取功能需要联系腾讯云短信技术支持(QQ:3012203387)，量大客户可以使用此功能批量拉取，其他客户不建议使用。

- **发送海外短信**

海外短信与国内短信发送类似, 发送海外短信只需替换相应国家码。



- **上传语音文件**

```php
use Qcloud\Sms\VoiceFileUploader;

try {
    // Note: 语音文件大小上传限制400K字节
    $filepath = "path/to/example.mp3";
    $fileContent = file_get_contents($filepath);
    if ($fileContent == false) {
        throw new \Exception("can not read file " . $filepath);
    }

    $contentType = VoiceFileUploader::MP3;
    $uploader = new VoiceFileUploader($appid, $appkey);
    $result = $uploader->upload($fileContent, $contentType);
    // 上传成功后，$rsp里会带有语音文件的fid
    $rsp = json_decode($result);
    echo $result;
} catch (\Exception $e) {
    echo var_dump($e);
}
```

> `Note` '语音文件上传'功能需要联系腾讯云短信技术支持(QQ:3012203387)才能开通


- **按语音文件fid发送语音通知**

```php
use Qcloud\Sms\FileVoiceSender;

try {
    // Note：这里$fid来自`上传语音文件`接口返回的响应，要按语音
    //    文件fid发送语音通知，需要先上传语音文件获取$fid
    $fid = "73844bb649ca38f37e596ec2781ce6a56a2a3a1b.mp3";
    $fvsender = new FileVoiceSender($appid, $appkey);
    $result = $fvsender->send("86", $phoneNumbers[0], $fid);
    $rsp = json_decode($result);
    echo $result;
} catch (\Exception $e) {
    echo var_dump($e);
}
```

> `Note` 按'语音文件fid发送语音通知'功能需要联系腾讯云短信技术支持(QQ:3012203387)才能开通


- **指定模板发送语音通知**

```php
use Qcloud\Sms\TtsVoiceSender;

try {
    $templateId = 1013;
    $params = ["54321"];
    $tvsender = new TtsVoiceSender($appid, $appkey);
    $result = $tvsender->send("86", $phoneNumbers[0], $templateId, $params);
    $rsp = json_decode($result);
    echo $result;
} catch (\Exception $e) {
    echo var_dump($e);
}
```
