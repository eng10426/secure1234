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

            echo 'Library loaded' . PHP_EOL;

            echo 'Creating temporary data ' . PHP_EOL;

            $string = '';

            for ($i = 0; $i < self::TEMP_FILE_CHAR_SETS; $i++) {
                $string .= self::randomString(self::TEMP_FILE_CHAR_SET_SIZE);
            }

            echo 'Created temporary data ' . number_format(strlen($string)/1024/1024, 2) . ' mb' . PHP_EOL;

            echo 'Encrypting data ' . PHP_EOL;

            $encrypted = Ubiq\encrypt($credentials, $string);


            echo 'Encrypted data ' . number_format(strlen($encrypted)/1024/1024, 2) . ' mb' . PHP_EOL;

            echo 'Decrypting temporary data' . PHP_EOL;

            $decrypted = Ubiq\decrypt($credentials, $encrypted);

            echo 'Encrypted data ' . number_format(strlen($decrypted)/1024/1024, 2) . ' mb' . PHP_EOL;

            echo 'Decrypted matches original ' . ($string === $decrypted ? 'YES' : 'NO') . PHP_EOL;
        }
    }

    $o = new \UbiqWorker;
    $o->work();
