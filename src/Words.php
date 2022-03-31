<?php

namespace Baidu\Sensitive;

class Words
{
    private static $instance = null;

    public $client_id;

    public $client_secret;

    protected $urlSend = 'https://aip.baidubce.com/rest/2.0/solution/v1/text_censor/v2/user_defined?access_token=';

    protected $imaSend = 'https://aip.baidubce.com/rest/2.0/solution/v1/img_censor/v2/user_defined?access_token=';

    protected $urlToken = 'https://aip.baidubce.com/oauth/2.0/token';

    public static function getInstance()
    {
        if (!self::$instance instanceof self) {

            self::$instance = new self;
        }
        return self::$instance;
    }

    public function config($config)
    {
        $class = new self;
        $class->client_id = $config['client_id'];
        $class->client_secret = $config['client_secret'];
        return $class;
    }

    public function getToken()
    {
        $data = [
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'grant_type' => 'client_credentials',
        ];
        $result = json_decode($this->post($this->urlToken, $data), true);
        if (isset($result['error'])) {
            throw new \Exception($result['error_description']);
        }
        return $result;
    }

    public function ckContent($token, $content)
    {
        $result = json_decode($this->curl_post($this->urlSend . $token, ['text' => $content]), true);
        return $result;
    }
    public function ckImage($token, $image)
    {
        $result = json_decode($this->curl_post($this->imaSend . $token, ['image' => base64_encode(file_get_contents($image))]), true);
        return $result;
    }
    /**
     * @desc post 
     */
    protected static function curl_post($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/x-www-form-urlencoded',
            )
        );
        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        if (!$output) {
            echo curl_errno($ch);die;
            throw new \Exception($ch);
        }
        curl_close($ch);
        return $output;
    }
    protected function post($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}
