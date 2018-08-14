# PHP Basic Cryptocurrency
A basic cryptocurrency app based on PHP Laravel - Lumen

### Functionalities :

#### Mining New Block
Mining new block is possible through a GET request to the following url :

`http://your-domain/blockchain/store`

#### Get My Chain
getting the chain you made is possible through a GET request to the following url :

`http://your-domain/blockchain/chain`

#### Chain Validation
validating the blockchain stored in your database is possible through a GET request to the following url :

`http://your-domain/blockchain/validation`

#### Replace The Longest Chain
Replacing (updating) the longest chain in the network is possible through a GET request to the following url :

`http://your-domain/blockchain/replace-chain`

#### Connect To Network Nodes
In real life cryptocurrecy softwares, There is an automation when a new node becomes available and broadcast its presence to the nearest nodes and then connects to them. 
In this basic cryptocurrency we have to send our desired nodes in a POST request to the application in order to connect to those nodes.

##### Request Header
`Content-Type : application/json` 

##### Sample POST Request Data
```
	{
		"nodes": [
			"http://localhost:8002",
			"http://localhost:8003"
		]
	}
```
##### The Connection
Make a POST request contained the above data to the following url :

`http//your-domain/nodes/connect`

#### Add Transaction
##### Request Header
`Content-Type : application/json` 

##### Sample POST Request Data
```
	{
		"sender": "sender public key",
		"receiver": "receiver public key or address",
		"amount": "an Integer number"
	}
```
##### Add To Mempool
Make a POST request contained the above data to the following url :

`http//your-domain/transactions/add`

#### Note 
###### After adding new transactions to the mempool, The current node should mine a new block in order to confirm the transactions.


# A Sample Scenario

#### Note 
In order to have a decentralized cryptocurrency, you may have multiple clones of this repository (for instance 3 clones) and run them on different ports.

------------
#### Running Nodes
##### Server #1
`$ php -S localhost:8001 -t public`
##### Server #2
`$ php -S localhost:8002 -t public`
##### Server #3
`$ php -S localhost:8003 -t public`

------------

#### Step #1
[Connect](#connect-to-network-nodes) all the nodes.
#### Step #2
[Mine a new block](#mining-new-block ) on server 8001 and then [get its chain](#get-my-chain).
You should see something like this :
```
	{
		"chain": [
		{
			"index": 1,
			"timestamp": "1534223754",
			"proof": 1,
			"prev_hash": "0",
			"transactions": []
		},
		{
			"index": 2,
			"timestamp": "1534224647",
			"proof": 3,
			"prev_hash": "7af7e9ba6611f5497f3979dd253a9defc11c7f3a388e4da1486aa95c535b5072",
			"transactions": [
				{
					"sender": "5b726907e3509",
					"receiver": "Mehrad",
					"amount": 1000
				}
			]
		}
		],
		"length": 2
	}
```
#### Step #3
[Update](#replace-the-longest-chain) the server 8001 chain. You should see the **The chain was the longest chain** message.
```
{
	"message": "The chain was the longest chain",
	"current_chain": [
		{
			"index": 1,
			"timestamp": "1534223754",
			"proof": 1,
			"prev_hash": "0",
			"transactions": []
		},
		{
			"index": 2,
			"timestamp": "1534224647",
			"proof": 3,
			"prev_hash": "7af7e9ba6611f5497f3979dd253a9defc11c7f3a388e4da1486aa95c535b5072",
			"transactions": [
				{
					"sender": "5b726907e3509",
					"receiver": "Mehrad",
					"amount": 1000
				}
			]
		}
	]
}
```
As you probably guessed this chain was the longest chain in network.

#### Step #4
[Get](#get-my-chain) the server 8002 chain. It should look like this :
```
{
	"chain": [
		{
			"index": 1,
			"timestamp": "1534222871",
			"proof": 1,
			"prev_hash": "0",
			"transactions": []
		}
	],
	"length": 1
}
```

#### Step #5
[Update](#replace-the-longest-chain) the server 8002 chain. You should see the **Chain replaced by the longest chain** message. So now server 8002 chain is synced to 8001 server.
```
{
	"message": "The chain was the longest chain",
	"current_chain": [
		{
			"index": 1,
			"timestamp": "1534223754",
			"proof": 1,
			"prev_hash": "0",
			"transactions": []
		},
		{
			"index": 2,
			"timestamp": "1534224647",
			"proof": 3,
			"prev_hash": "7af7e9ba6611f5497f3979dd253a9defc11c7f3a388e4da1486aa95c535b5072",
			"transactions": [
				{
					"sender": "5b726907e3509",
					"receiver": "Mehrad",
					"amount": 1000
				}
			]
		}
	]
}
```
You can to the same as server 8002 for server 8003 to sync up the chains.

#### Step #6
[Add new transaction](#add-transaction) to server 8001 and [mine a new block](#mining-new-block) in order to confirm the transaction. 

##### Note 
To sync the nodes you may do the [Step #5](#step-5) again.
