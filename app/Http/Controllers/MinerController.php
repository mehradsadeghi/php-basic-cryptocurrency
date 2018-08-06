<?php

namespace App\Http\Controllers;

use App\Facades\Blockchain;

class MinerController extends Controller {

    public function chain() {
        $blockchain = new Blockchain();
        $chain = $blockchain->getChain();
        return response()->json($chain);
    }

    public function store() {

        $blockchain = new Blockchain();

        $proof = $blockchain->proofOfWork();
        $lastBlock = $blockchain->getLastBlock();
        $hashedPrevBlock = $blockchain->hashTheBlock($lastBlock);

        $block = $blockchain->createBlock($proof, $hashedPrevBlock);

        return response()->json($block);
    }

    public function chainValidation() {
        $data = (new Blockchain())->chainValidation();
        return response()->json($data);
    }
}
