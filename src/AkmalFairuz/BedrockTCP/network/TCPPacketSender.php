<?php

declare(strict_types=1);

namespace AkmalFairuz\BedrockTCP\network;

use AkmalFairuz\Sobana\server\ServerSession;
use pocketmine\network\mcpe\PacketSender;
use pocketmine\utils\Binary;
use function strlen;

class TCPPacketSender implements PacketSender{

    private bool $closed = false;

    public function __construct(
        private ServerSession $session){
    }

    public function send(string $payload, bool $immediate): void{
        $this->session->write(Binary::writeInt(strlen($payload)) . $payload);
        if($immediate) {
            $this->session->flush();
        }
    }

    public function close(string $reason = "unknown reason"): void{
        if($this->closed) {
            return;
        }
        $this->closed = true;
        $this->session->close();
    }
}