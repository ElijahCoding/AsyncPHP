<?php

namespace App;

use Spatie\Async\Pool;

class LogService
{
    CONST CREDIT = 'Заявка на кредит';
    CONST KASKO = 'Заявка на страхование КАСКО';
    CONST OSAGO = 'Заявка на страхование ОСАГО';
    CONST REFINANCING = 'Заявка на рефинансирование';

    const VALUES = [
        self::CREDIT,
        self::KASKO,
        self::OSAGO,
        self::REFINANCING
    ];

    private static $_file = __DIR__ . '/../logs.txt';

    private static $data = [];

    private $_poll;

    public function __construct()
    {
        $this->_poll = Pool::create();
    }

    public static function randomValue()
    {
        return self::VALUES[array_rand(self::VALUES)];
    }

    public static function generateData()
    {
        for ($i = 1; $i <= 10000; $i++) {
             array_push(self::$data, self::randomValue());
        }
        return new self;
    }

    public static function createFile()
    {
        if (!file_exists(self::$_file)) {
            touch(self::$_file);
        }
        return new self;
    }

    public function writeFile()
    {
        $t0 = new \Datetime();

        foreach (self::$data as $item) {
            $this->_poll[] = async(function () use ($item) {
                sleep(2);
                $stream = fopen(self::$_file, 'a');
                fwrite($stream, $item . "\n");
                fclose($stream);
            });
        }

        await($this->_poll);

        $t1 = new \Datetime();
        $interval = $t1->getTimestamp() - $t0->getTimestamp();

        return "Time interval in seconds: {$interval}";
    }
}