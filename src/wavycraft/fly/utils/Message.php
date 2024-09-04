<?php

declare(strict_types=1);

namespace wavycraft\fly\utils;

use pocketmine\player\Player;
use pocketmine\utils\SingletonTrait;

use wavycraft\fly\Fly;

class Message {
    use SingletonTrait;

    private $plugin;

    public function __construct() {
        $this->plugin = Fly::getInstance();
    }

    public function sendFlyTitle(Player $player, $titleKey, $subtitleKey) {
        $title = $this->plugin->getConfig()->get($titleKey);
        $subtitle = $this->plugin->getConfig()->get($subtitleKey);
        $player->sendTitle($title, $subtitle);
    }
}