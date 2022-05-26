<?php

declare(strict_types=1);

namespace AkmalFairuz\BedrockTCP\network;

use AkmalFairuz\Sobana\server\ServerManager;
use pocketmine\network\AdvancedNetworkInterface;
use pocketmine\network\Network;
use pocketmine\Server;

class TCPServerManager extends ServerManager implements AdvancedNetworkInterface{

    public function __construct(protected Server $server, protected Network $network, string $ip, int $port, ?string $sessionClass = null, ?string $encoderClass = null, ?string $decoderClass = null){
        parent::__construct($ip, $port, $sessionClass, $encoderClass, $decoderClass);
    }

    /**
     * @return Network
     */
    public function getNetwork(): Network{
        return $this->network;
    }

    /**
     * @return Server
     */
    public function getServer(): Server{
        return $this->server;
    }

    public function setName(string $name): void{}

    public function tick(): void{}

    public function blockAddress(string $address, int $timeout = 300): void{}

    public function unblockAddress(string $address): void{}

    public function setNetwork(Network $network): void{
        $this->network = $network;
    }

    public function sendRawPacket(string $address, int $port, string $payload): void{}

    public function addRawPacketFilter(string $regex): void{}
}