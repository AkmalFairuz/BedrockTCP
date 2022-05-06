<?php

declare(strict_types=1);

namespace AkmalFairuz\BedrockTCP\network;

use AkmalFairuz\BedrockTCP\compressor\TCPCompressor;
use AkmalFairuz\Sobana\server\ServerSession;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\PacketPool;

/**
 * @property TCPServerManager $serverManager
 */

class TCPSession extends ServerSession{

    private NetworkSession $networkSession;

    public function onConnect(): void{
        $this->networkSession = new NetworkSession(
            $this->serverManager->getServer(),
            $this->serverManager->getNetwork()->getSessionManager(),
            PacketPool::getInstance(),
            new TCPPacketSender($this),
            $this->serverManager->getBroadcaster(),
            TCPCompressor::getInstance(),
            $this->getIp(),
            $this->getPort()
        );
    }

    public function handlePacket(string $packet): void{
        $this->networkSession->handleEncoded($packet);
    }

    public function onClose(): void{
        $this->networkSession->onClientDisconnect("unknown"); // TODO: reason
    }
}