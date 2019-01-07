<?php

namespace Qcloud\Sms;

use Qcloud\Sms\SmsSenderUtil;


/**
 * 上传语音文件类
 *
 */
class VoiceFileUploader
{
    private $url;
    private $appid;
    private $appkey;
    private $util;

    const WAV = "audio/wav";
    const MP3 = "audio/mpeg";

    /**
     * 构造函数
     *
     * @param string $appid  sdkappid
     * @param string $appkey sdkappid对应的appkey
     */
    public function __construct($appid, $appkey)
    {
        $this->url = "https://cloud.tim.qq.com/v5/tlsvoicesvr/uploadvoicefile";
        $this->appid =  $appid;
        $this->appkey = $appkey;
        $this->util = new SmsSenderUtil();
    }

    /**
     *
     * 上传语音文件
     *
     * @param string $fileContent  语音文件内容
     * @param string $contentType  语音文件类型，目前支持 VoiceFileUploader::WAV 和 VoiceFileUploader::MP3
     * @return string 应答json字符串，详细内容参见腾讯云协议文档
     */
    public function upload($fileContent, $contentType)
    {
        assert($contentType == self::WAV || $contentType == self::MP3);

        $random = $this->util->getRandom();
        $curTime = time();
        $fileSha1Sum = $this->util->sha1sum($fileContent);
        $auth = $this->util->calculateAuth($this->appkey, $random,
            $curTime, $fileSha1Sum);

        $req = new \stdClass();
        $req->url = $this->url . "?sdkappid=" . $this->appid
            . "&random=" . $random . "&time=" . $curTime;
        $req->body = $fileContent;
        $req->headers = array(
            "Content-Type: " . $contentType,
            "x-content-sha1: " . $fileSha1Sum,
            "Authorization: " . $auth
        );

        return $this->util->fetch($req);
    }
}
