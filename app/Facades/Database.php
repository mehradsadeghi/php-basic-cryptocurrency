<?php

namespace App\Facades;

class Database {

    private static $database = 'database/blockchain.json';

    public static function storeBlock($data) {

        $database = self::storagPath(self::$database);

        $oldData = file_get_contents($database);

        $tempData = (array)json_decode($oldData);

        array_push($tempData, $data);
        $jsonData = json_encode($tempData);

        file_put_contents($database, $jsonData);
    }

    public static function fetchChain() {
        $database = self::storagPath(self::$database);
        $data = json_decode(file_get_contents($database), true);
        return $data;
    }

    private static function storagPath($path = null) {
        return app()->storagePath($path);
    }
}
