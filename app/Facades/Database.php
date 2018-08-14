<?php

namespace App\Facades;

class Database {

    private static $blockchainPath = 'database/blockchain.json';
    private static $nodesPath = 'database/nodes.json';
    private static $transactionsPath = 'database/transactions.json';

    public static function storeBlock($data) {
        $database = self::storagPath(self::$blockchainPath);
        self::store($database, $data);
    }

    public static function addTransaction($transaction) {
        $database = self::storagPath(self::$transactionsPath);
        self::store($database, $transaction);
    }

    public static function addNode($node) {
        $database = self::storagPath(self::$nodesPath);
        self::store($database, $node);
    }

    public static function truncateTransactions() {
        $database = self::storagPath(self::$transactionsPath);
        self::truncate($database);
    }

    public static function fetchTransactions() {
        $database = self::storagPath(self::$transactionsPath);
        $data = self::fetch($database);
        return $data;
    }

    public static function fetchNodes() {
        $database = self::storagPath(self::$nodesPath);
        $data = self::fetch($database);
        return $data;
    }

    public static function fetchChain() {
        $database = self::storagPath(self::$blockchainPath);
        $data = self::fetch($database);
        return $data;
    }

    public static function replaceChain($chain) {

        $database = self::storagPath(self::$blockchainPath);

        self::truncate($database);
        self::store($chain);
    }

    private static function fetch($database) {
        $contents = file_get_contents($database);
        $data = json_decode($contents, true);
        return $data;
    }

    private static function truncate($database) {
        file_put_contents($database, '');
    }

    private static function store($database, $data) {

        $oldData = file_get_contents($database);

        $tempData = (array)json_decode($oldData);

        array_push($tempData, $data);
        $jsonData = json_encode($tempData);

        file_put_contents($database, $jsonData);
    }

    private static function storagPath($path = null) {
        return app()->storagePath($path);
    }
}
