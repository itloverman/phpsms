<?php

namespace Toplan\PhpSms;

abstract class Agent
{
    const SUCCESS = 'success';
    const INFO = 'info';
    const CODE = 'code';

    /**
     * The configuration information.
     *
     * @var array
     */
    protected $config = [];

    /**
     * The result data.
     *
     * @var array
     */
    protected $result = [];

    /**
     * Constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->reset();
        $this->config($config);
    }

    /**
     * Reset states.
     */
    public function reset()
    {
        $this->result = [
            self::SUCCESS => false,
            self::INFO    => null,
            self::CODE    => 0,
        ];
    }

    /**
     * Get or set the configuration information.
     *
     * @param mixed $key
     * @param mixed $value
     * @param bool  $override
     *
     * @return mixed
     */
    public function config($key = null, $value = null, $override = false)
    {
        if (is_array($key) && is_bool($value)) {
            $override = $value;
        }

        return Util::operateArray($this->config, $key, $value, null, null, $override);
    }

    /**
     * SMS send process.
     *
     * @param       $to
     * @param       $content
     * @param       $tempId
     * @param array $data
     * @param array $params
     */
    public function sendSms($to, $content, $tempId = null, array $data = [], array $params = [])
    {
        $this->reset();
        if ($content) {
            if ($this instanceof ContentSms) {
                $this->sendContentSms($to, $content, $params);
            }
            $content = null;
        } elseif ($tempId) {
            if ($this instanceof TemplateSms) {
                $this->sendTemplateSms($to, $tempId, $data, $params);
            }
            $tempId = null;
        }
        if (!$this->result(self::SUCCESS) && ($content || $tempId)) {
            $this->sendSms($to, $content, $tempId, $data, $params);
        }
    }

    /**
     * Voice send process.
     *
     * @param       $to
     * @param       $content
     * @param       $tempId
     * @param array $data
     * @param       $code
     * @param       $fileId
     * @param array $params
     */
    public function sendVoice($to, $content, $tempId = null, array $data = [], $code = null, $fileId = null, array $params = [])
    {
        $this->reset();
        if ($code && $this instanceof VoiceCode) {
            $this->sendVoiceCode($to, $code, $params);
        } elseif ($content && $this instanceof ContentVoice) {
            $this->sendContentVoice($to, $content, $params);
        } elseif ($tempId && $this instanceof TemplateVoice) {
            $this->sendTemplateVoice($to, $tempId, $data, $params);
        } elseif ($fileId && $this instanceof FileVoice) {
            $this->sendFileVoice($to, $fileId, $params);
        }
    }

    /**
     * cURl
     *
     * @codeCoverageIgnore
     *
     * @param string $url    [请求地址]
     * @param array  $params [请求参数]
     * @param bool   $post   [是否post]
     * @param array  $opts   [curl设置项]
     *
     * @return array ['request', 'response']
     *               request:是否请求成功
     *               response:响应数据
     */
    public static function curl($url, $params = [], $post = false, array $opts = [])
    {
        if (is_array($post)) {
            $opts = $post;
            $post = false;
        }
        if (is_bool($params)) {
            $post = $params;
            $params = [];
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            $params = http_build_query($params);
            curl_setopt($ch, CURLOPT_URL, $params ? "$url?$params" : $url);
        }
        foreach ($opts as $key => $value) {
            curl_setopt($ch, $key, $value);
        }
        $response = curl_exec($ch);

        $request = $response !== false;
        if (!$request) {
            $response = curl_getinfo($ch);
        }
        curl_close($ch);

        return compact('request', 'response');
    }

    /**
     * Get or set the result data.
     *
     * @param $name
     * @param $value
     *
     * @return mixed
     */
    public function result($name = null, $value = null)
    {
        if ($name === null) {
            return $this->result;
        }
        if (array_key_exists($name, $this->result)) {
            if ($value === null) {
                return $this->result[$name];
            }
            $this->result[$name] = $value;
        }
    }

    /**
     * Overload object properties.
     *
     * @param $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->config($name);
    }

    /**
     * When using isset() or empty() on inaccessible object properties,
     * the __isset() overloading method will be called.
     *
     * @param $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->config[$name]);
    }
}
