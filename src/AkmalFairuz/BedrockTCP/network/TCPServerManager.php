<?php

declare(strict_types=1);

namespace AkmalFairuz\BedrockTCP\network;

use AkmalFairuz\Sobana\server\ServerManager;
use pocketmine\network\mcpe\StandardPacketBroadcaster;
use pocketmine\network\Network;
use pocketmine\network\NetworkInterface;
use pocketmine\Server;

class TCPServerManager extends ServerManager implements NetworkInterface{

    private StandardPacketBroadcaster $broadcaster;

    public function __construct(protected Server $server, protected Network $network, string $ip, int $port, ?string $sessionClass = null, ?string $encoderClass = null, ?string $decoderClass = null){
        parent::__construct($ip, $port, $sessionClass, $encoderClass, $decoderClass);
        $this->broadcaster = new StandardPacketBroadcaster($this->server, 503);
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

    /**
     * @return StandardPacketBroadcaster
     */
    public function getBroadcaster(): StandardPacketBroadcaster{
        return $this->broadcaster;
    }
}