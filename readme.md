# PHP Basic Blockchain
A basic blockchain app based on PHP Laravel - Lumen

# Functionalities :
  - Mine new block based on given target for POW
  - Checks the validity of the chain
  - Fetch all blocks of the chain

## Usage  

#### Run the server : 
```sh
$ php -S localhost:8000 -t public
```

###### Then you my run your http client and make requests based on the following routes :

```sh
$router->get('/chain', ... );
$router->get('/validation', ... );
$router->post('/store', ... );
```

### Note

You can change the difficulty of POW by adding more zores to **$target** variable in **App\Facades\Blockchain.php**

```
class Blockchain {
    private $hashAlgorithm = 'sha256';
    private $target = '0';	// here you go
    private $genesisProof = 1;
    private $genesisPrevHash = '0';
	. . .
```