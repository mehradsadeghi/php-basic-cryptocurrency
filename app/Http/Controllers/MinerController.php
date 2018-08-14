<?php

namespace App\Http\Controllers;

use App\Facades\Blockchain;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MinerController extends Controller {

    private $blockchain;
    private $reward = 1000;
    private $rewardSender;
    private $rewardReceiver = 'Mehrad';

    public function __construct() {
        $this->blockchain = new Blockchain();
        $this->rewardSender = uniqid();
    }

    public function chain() {

        $chain = $this->blockchain->getChain();

        return response()->json([
            'chain' => $chain,
            'length' => count($chain)
        ]);
    }

    public function store() {

        $proof = $this->blockchain->proofOfWork();
        $lastBlock = $this->blockchain->getLastBlock();
        $hashedPrevBlock = $this->blockchain->hashTheBlock($lastBlock);
        $this->blockchain->addTransaction(['sender' => $this->rewardSender, 'receiver' => $this->rewardReceiver, 'amount' => $this->reward]);

        $block = $this->blockchain->createBlock($proof, $hashedPrevBlock);

        return response()->json($block);
    }

    public function chainValidation() {
        $data = $this->blockchain->chainValidation();
        return response()->json($data);
    }

    public function addTransaction(Request $request) {

        $this->validate($request, [
            'sender'   => 'required|string',
            'receiver' => 'required|string',
            'amount'   => 'required|int',
        ]);

        $data = $request->all();
        $index = $this->blockchain->addTransaction($data);

        return response()->json([
            'message' => "This transaction will be added to block $index"
        ])->setStatusCode(Response::HTTP_CREATED);
    }

    public function connectNodes(Request $request) {

        $data = $request->all();

        $this->validate($request, [
            'nodes'   => 'required|array',
            'nodes.*' => 'required|url',
        ]);

        foreach($data['nodes'] as $node) {
            $this->blockchain->addNode($node);
        }

        return response()->json([
            'message' => 'All nodes are connected',
            'total_nodes' => $this->blockchain->getNodes()
        ]);
    }

    public function replaceChain() {

        $isChainReplaced = $this->blockchain->replaceChain();

        if($isChainReplaced) {

            $response = [
                'message' => 'Chain replaced by the longest chain',
                'new_chain' => $this->blockchain->getChain()
            ];

        } else {

            $response = [
                'message' => 'The chain was the longest chain',
                'current_chain' => $this->blockchain->getChain()
            ];

        }

        return response()->json($response);
    }
}
