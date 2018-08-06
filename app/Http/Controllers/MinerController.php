<?php

namespace App\Http\Controllers;

use App\Facades\Blockchain;

class MinerController extends Controller {

    private $blockchain;

    public function __construct() {
        $this->blockchain = new Blockchain();
    }

    public function chain() {
        $chain = $this->blockchain->getChain();
        return response()->json($chain);
    }

    public function store() {

        $proof = $this->blockchain->proofOfWork();
        $lastBlock = $this->blockchain->getLastBlock();
        $hashedPrevBlock = $this->blockchain->hashTheBlock($lastBlock);

        $block = $this->blockchain->createBlock($proof, $hashedPrevBlock);

        return response()->json($block);
    }

    public function chainValidation() {
        $data = $this->blockchain->chainValidation();
        return response()->json($data);
    }
}
