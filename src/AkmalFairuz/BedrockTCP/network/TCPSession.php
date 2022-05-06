<?php

declare(strict_types=1);

namespace AkmalFairuz\BedrockTCP\network;

use AkmalFairuz\BedrockTCP\compressor\TCPCompressor;
use AkmalFairuz\Sobana\server\ServerSession;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\PacketPool;
use pocketmine\network\PacketHandlingException;
use pocketmine\utils\Utils;

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
        try{
            $this->networkSession->handleEncoded($packet);
        }catch(PacketHandlingException $e) {
            $errorId = bin2hex(random_bytes(6));

            $logger = $this->networkSession->getLogger();
            $logger->error("Bad packet (error ID $errorId): " . $e->getMessage());

            //intentionally doesn't use logException, we don't want spammy packet error traces to appear in release mode
            $logger->debug(implode("\n", Utils::printableExceptionInfo($e)));
            $this->networkSession->disconnect("Packet processing error (Error ID: $errorId)");
        }
    }

    public function onClose(): void{
        $this->networkSession->onClientDisconnect("unknown"); // TODO: reason
    }
}