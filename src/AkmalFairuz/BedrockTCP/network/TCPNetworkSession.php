<?php

declare(strict_types=1);

namespace AkmalFairuz\BedrockTCP\network;

use pocketmine\network\mcpe\NetworkSession;

class TCPNetworkSession extends NetworkSession{

    public int $downstreamPing = 0;
    public int $upstreamPing = 0;

    public function getDownstreamPing(): int{
        return $this->downstreamPing;
    }

    public function getUpstreamPing(): int{
        return $this->upstreamPing;
    }

    public function getPing(): int{
        return $this->downstreamPing;
    }

    public function getTotalPing(): int{
        return $this->downstreamPing + $this->upstreamPing;
    }

    public function isLoggedIn(): bool{
        return $this->loggedIn;
    }
}