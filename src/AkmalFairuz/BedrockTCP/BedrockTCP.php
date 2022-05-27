<?php

declare(strict_types=1);

namespace AkmalFairuz\BedrockTCP;

use AkmalFairuz\BedrockTCP\network\TCPNetworkSession;
use AkmalFairuz\BedrockTCP\network\TCPServerManager;
use AkmalFairuz\BedrockTCP\network\TCPSession;
use AkmalFairuz\BedrockTCP\packet\NSL;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\event\server\NetworkInterfaceRegisterEvent;
use pocketmine\network\mcpe\protocol\PacketPool;
use pocketmine\network\mcpe\protocol\TickSyncPacket;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use function function_exists;

class BedrockTCP extends PluginBase implements Listener{

    public function onEnable(): void{
        /*if(!function_exists("zstd_compress")) {
            $this->getLogger()->error("This plugin require zstd extension. Disabling plugin...");
            $this->getServer()->getPluginManager()->disablePlugin($this);
            return;
        }*/
        $ip = Server::getInstance()->getIp();
        $port = Server::getInstance()->getPort();
        $this->getServer()->getNetwork()->registerInterface(new TCPServerManager($this, $this->getServer(), $this->getServer()->getNetwork(), $ip, $port, TCPSession::class));
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        PacketPool::getInstance()->registerPacket(new NSL());
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

    /**
     * @param DataPacketReceiveEvent $event
     * @priority LOWEST
     * @ignoreCancelled true
     */
    public function onDataPacketReceive(DataPacketReceiveEvent $event) {
        $packet = $event->getPacket();
        /** @var TCPNetworkSession $origin */
        $origin = $event->getOrigin();
        if($packet instanceof NSL) {
            if($packet->timestamp === 0 && $packet->needResponse) {
                $origin->sendDataPacket($packet, true);
                $event->cancel();
            }
        }elseif($packet instanceof TickSyncPacket) {
            $origin->upstreamPing = $packet->getClientSendTime();
            $origin->downstreamPing = $packet->getServerReceiveTime();
            $event->cancel();
        }
    }
}