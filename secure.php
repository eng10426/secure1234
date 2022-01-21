<?php

    class UbiqWorker
    {
        const TEMP_FILE_CHAR_SETS = 1024*1024;
        const TEMP_FILE_CHAR_SET_SIZE = 8;

        private static function randomString($length = 10, $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
        {
            $characters_length = strlen($characters);
            $random = '';

            for ($i = 0; $i < $length; $i++) {
                $random .= $characters[rand(0, $characters_length - 1)];
            }

            return $random;
        }

        public function work()
        {
            require realpath(dirname(__FILE__) . '/../ubiq-php/src/Ubiq.php');

            $credentials = new Ubiq\Credentials();
            $credentials->load('ubiq.credentials');

            echo date('Y-m-d H:i:s) . ' Library loaded' . PHP_EOL;

            echo date('Y-m-d H:i:s) . ' Creating temporary data ' . PHP_EOL;

            $string = '';

            for ($i = 0; $i < self::TEMP_FILE_CHAR_SETS; $i++) {
                $string .= self::randomString(self::TEMP_FILE_CHAR_SET_SIZE);
            }

            echo date('Y-m-d H:i:s) . ' Created temporary data ' . number_format(strlen($string)/1024/1024, 2) . ' mb' . PHP_EOL;

            echo date('Y-m-d H:i:s) . ' Encrypting data ' . PHP_EOL;

            $encrypted = Ubiq\encrypt($credentials, $string);


            echo date('Y-m-d H:i:s) . ' Encrypted data ' . number_format(strlen($encrypted)/1024/1024, 2) . ' mb' . PHP_EOL;

            echo date('Y-m-d H:i:s) . ' Decrypting temporary data' . PHP_EOL;

            $decrypted = Ubiq\decrypt($credentials, $encrypted);

            echo date('Y-m-d H:i:s) . ' Encrypted data ' . number_format(strlen($decrypted)/1024/1024, 2) . ' mb' . PHP_EOL;

            echo date('Y-m-d H:i:s) . ' Decrypted matches original ' . ($string === $decrypted ? 'YES' : 'NO') . PHP_EOL;
        }
    }

    $o = new \UbiqWorker;
    $o->work();
