<?php

declare(strict_types=1);

namespace AkmalFairuz\BedrockTCP;

use AkmalFairuz\BedrockTCP\network\TCPServerManager;
use AkmalFairuz\BedrockTCP\network\TCPSession;
use pocketmine\network\mcpe\raklib\RakLibInterface;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class BedrockTCP extends PluginBase{

    public function onEnable(): void{
        $ip = Server::getInstance()->getIp();
        $port = Server::getInstance()->getPort();
        foreach($this->getServer()->getNetwork()->getInterfaces() as $interface) {
            if($interface instanceof RakLibInterface) {
                $interface->shutdown();
            }
        }
        $this->getServer()->getNetwork()->registerInterface(new TCPServerManager($this->getServer(), $this->getServer()->getNetwork(), $ip, $port, TCPSession::class));
    }
}