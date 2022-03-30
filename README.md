#百度智能云 敏感字检测

https://cloud.baidu.com/doc/ANTIPORN/s/Rk3h6xb3i
### config
```
        $config = [
            'client_id' => '',
            'client_secret' => '',
        ];

```
### 获取Token

```

$token = \Demo\test\Test::getInstance()->config($config)->getToken();

echo $token['access_token'];die;

```

### 检测文本敏感字

```
$data = \Baidu\Sensitive\Words::getInstance()->config($config)->ckContent($token, "文本内容");

```