<?php

declare(strict_types=1);

namespace AkmalFairuz\BedrockTCP\network;

use pocketmine\network\mcpe\handler\InGamePacketHandler;
use pocketmine\network\mcpe\handler\PacketHandler;
use pocketmine\network\mcpe\NetworkSession;

class TCPNetworkSession extends NetworkSession{

    public function setHandler(?PacketHandler $handler): void{
        if($handler instanceof InGamePacketHandler) {
            $handler = new TCPInGamePacketHandler($this->getPlayer(), $this, $this->getInvManager());
        }
        parent::setHandler($handler);
    }
}