<?php

namespace App\Facades;

class Blockchain {
    
    private $hashAlgorithm = 'sha256';
    private $target = '0';
    private $genesisProof = 1;
    private $genesisPrevHash = '0';

    public function __construct() {
        $this->createGenesisBlock($this->genesisProof, $this->genesisPrevHash);
    }

    public function getChain() {
        return Database::fetchChain();
    }

    public function getNodes() {
        return Database::fetchNodes();
    }

    public function createBlock($proof, $prev_hash) {
        
        $block = [
            'index'        => count($this->getChain()) + 1,
            'timestamp'    => (string)time(),
            'proof'        => $proof,
            'prev_hash'    => $prev_hash,
            'transactions' => Database::fetchTransactions() ?: []
        ];

        Database::storeBlock($block);

        Database::truncateTransactions();

        return $block;
    }

    public function getLastBlock() {
        $chain = $this->getChain();
        return end($chain);
    }

    public function proofOfWork() {

        $prevBlock = $this->getLastBlock();
        $hashedPrevBlock = $this->hashTheBlock($prevBlock);

        $index = $prevBlock['index'] + 1;
        $proof = $this->genesisProof + 1;

        $checkProof = false;

        while($checkProof === false) {
            
            $data = $proof . $hashedPrevBlock . $index;
            $hash = hash($this->hashAlgorithm, $data);

            if(substr($hash, 0, strlen($this->target)) === $this->target) {
                $checkProof = true;
            } else {
                $proof++;
            }
        }

        return $proof;
    }

    public function hashTheBlock($block) {
        $block = json_encode($block);
        $hash = hash($this->hashAlgorithm, $block);
        return $hash;
    }

    public function chainValidation($chain = null) {

        if($chain == null) {
            $chain = $this->getChain();
        }

        $blocksCount = count($chain);

        if($blocksCount < 2) return false;

        $prevBlock = $chain[0];
        $i = 1;
        
        while($i < $blocksCount) {
            
            $block = (array)$chain[$i];
            $hashedPrevBlock = $this->hashTheBlock($prevBlock);

            if($block['prev_hash'] != $hashedPrevBlock) return false;

            $data = $block['proof'] . $block['prev_hash'] . $block['index'];
            $hash = hash($this->hashAlgorithm, $data);

            if(substr($hash, 0, strlen($this->target)) !== $this->target) return false;

            $prevBlock = $block;
            $i++;
        }

        return true;
    }

    public function addTransaction($data) {

        Database::addTransaction([
            'sender'   => $data['sender'],
            'receiver' => $data['receiver'],
            'amount'   => $data['amount']
        ]);

        $latestBlock = $this->getLastBlock();

        return $latestBlock['index'] + 1;
    }

    public function addNode($address) {

        $node = parse_url($address, PHP_URL_HOST);
        $port = parse_url($address, PHP_URL_PORT);

        if(!empty($port)) {
            $node .= ":$port";
        }

        Database::addNode($node);
    }

    public function replaceChain() {

        $network = $this->getNodes();
        $longestChain = null;
        $maxLength = count($this->getChain());

        foreach($network as $node) {

            $response = $this->getRequest("http://$node/blockchain/chain");
            $response = json_decode($response);

            if(!empty($response)) {
                $length = $response->length;
                $chain  = $response->chain;

                if($this->chainValidation($chain) and $length > $maxLength) {
                    $maxLength = $length;
                    $longestChain = $chain;
                }
            }
        }

        if(!empty($longestChain)) {
            $longestChain = json_encode($longestChain);
            Database::replaceChain($longestChain);
            return true;
        }

        return false;
    }

    private function getRequest($url) {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $data = curl_exec($ch);

        curl_close($ch);

        return $data;
    }

    private function createGenesisBlock() {
        if($this->isChainInGenesisBlock()) {
            $this->createBlock(1, '0');
        }
    }

    private function isChainInGenesisBlock() {
        $data = $this->getChain();
        if(empty($data)) return true;
        return false;
    }
}