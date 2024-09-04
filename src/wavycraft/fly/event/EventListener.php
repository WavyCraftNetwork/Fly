<?php

declare(strict_types=1);

namespace wavycraft\fly\event;

use pocketmine\player\Player;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByBlockEvent;
use pocketmine\event\player\PlayerMoveEvent;
use wavycraft\fly\command\FlyCommand;

class EventListener implements Listener {

    private $flyCommand;

    public function __construct(FlyCommand $flyCommand) {
        $this->flyCommand = $flyCommand;
    }

    public function onEntityDamage(EntityDamageEvent $event) {
        $entity = $event->getEntity();

        if ($entity instanceof Player) {
            $playerName = $entity->getName();

            if ($event->getCause() === EntityDamageEvent::CAUSE_FALL && isset($this->flyCommand->getFallProtection()[$playerName])) {
                $event->cancel();
                unset($this->flyCommand->getFallProtection()[$playerName]);
            }
        }
    }

    public function onPlayerMove(PlayerMoveEvent $event) {
        $player = $event->getPlayer();
        $playerName = $player->getName();

        if (isset($this->flyCommand->getFallProtection()[$playerName])) {
            if ($player->getFallDistance() === 0) {
                unset($this->flyCommand->getFallProtection()[$playerName]);
            }
        }
    }
}