<?php

declare(strict_types=1);

namespace AkmalFairuz\BedrockTCP\network;

use pocketmine\network\mcpe\handler\InGamePacketHandler;
use pocketmine\network\mcpe\protocol\NetworkStackLatencyPacket;
use pocketmine\network\mcpe\protocol\TickSyncPacket;

class TCPInGamePacketHandler extends InGamePacketHandler{

    public function handleNetworkStackLatency(NetworkStackLatencyPacket $packet): bool{
        if($packet->timestamp === 0) {
            $this->session->sendDataPacket($packet);
            return true;
        }
        return false;
    }

    public function handleTickSync(TickSyncPacket $packet): bool{
        // TODO: TickSyncPacket is used in the anti-cheat plugin and check if the TickSyncPacket is sent from client

        // Store upstream ping on your core
        // $upstreamPing = $packet->getClientSendTime();
        $downstreamPing = $packet->getServerReceiveTime();
        $this->session->updatePing($downstreamPing);
        return true;
    }
}