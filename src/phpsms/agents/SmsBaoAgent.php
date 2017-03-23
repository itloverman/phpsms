<?php

namespace Toplan\PhpSms;

/**
 * Class SmsBaoAgent
 *
 * @property string $username
 * @property string $password
 */
class SmsBaoAgent extends Agent
{
    protected $resultArr = [
        '0'  => '发送成功',
        '-1' => '参数不全',
        '30' => '密码错误',
        '40' => '账号不存在',
        '41' => '余额不足',
        '42' => '帐户已过期',
        '43' => 'IP地址限制',
        '50' => '内容含有敏感词',
        '51' => '手机号码不正确',
    ];

    public function sendSms($to, $content, $tempId, array $data)
    {
        $this->sendContentSms($to, $content);
    }

    /**
     * Content SMS send process.
     *
     * @param $to
     * @param $content
     */
    public function sendContentSms($to, $content)
    {
        $url = 'http://api.smsbao.com/sms';
        $params = [
            'u' => $this->username,
            'p' => md5($this->password),
            'm' => $to,
            'c' => $content,
        ];
        $result = $this->curl($url, $params);
        $this->setResult($result);
    }

    /**
     * Template SMS send process.
     *
     * @param       $to
     * @param       $tempId
     * @param array $tempData
     */
    public function sendTemplateSms($to, $tempId, array $tempData)
    {
    }

    /**
     * Voice verify send process.
     *
     * @param       $to
     * @param       $code
     * @param       $tempId
     * @param array $tempData
     */
    public function voiceVerify($to, $code, $tempId, array $tempData)
    {
        $url = 'http://api.smsbao.com/voice';
        $params = [
            'u' => $this->username,
            'p' => md5($this->password),
            'm' => $to,
            'c' => $code,
        ];
        $result = $this->curl($url, $params);
        $this->setResult($result);
    }

    protected function setResult($result)
    {
        if ($result['request']) {
            $result = $result['response'];
            $msg = array_key_exists($result, $this->resultArr) ? $this->resultArr[$result] : 'unknown error';
            $this->result(Agent::INFO, json_encode(['code' => $result, 'msg' => $msg]));
            $this->result(Agent::SUCCESS, $result === '0');
            $this->result(Agent::CODE, $result);
        } else {
            $this->result(Agent::INFO, 'request failed');
        }
    }
}
