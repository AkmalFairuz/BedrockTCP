<?php

declare(strict_types=1);

namespace AkmalFairuz\BedrockTCP;

use AkmalFairuz\BedrockTCP\network\TCPServerManager;
use AkmalFairuz\BedrockTCP\network\TCPSession;
use pocketmine\event\Listener;
use pocketmine\event\server\NetworkInterfaceRegisterEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use function function_exists;

class BedrockTCP extends PluginBase implements Listener{

    public function onEnable(): void{
        if(!function_exists("zstd_compress")) {
            $this->getLogger()->error("This plugin require zstd extension. Disabling plugin...");
            $this->getServer()->getPluginManager()->disablePlugin($this);
            return;
        }
        $ip = Server::getInstance()->getIp();
        $port = Server::getInstance()->getPort();
        $this->getServer()->getNetwork()->registerInterface(new TCPServerManager($this->getServer(), $this->getServer()->getNetwork(), $ip, $port, TCPSession::class));
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    /**
     * @param NetworkInterfaceRegisterEvent $event
     * @priority HIGHEST
     */
    public function onNetworkInterfaceRegister(NetworkInterfaceRegisterEvent $event) {
        $interface = $event->getInterface();
        if(!$interface instanceof TCPServerManager) {
            $event->cancel();
        }
    }
}