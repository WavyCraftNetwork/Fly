<?php

declare(strict_types=1);

namespace wavycraft\fly\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use wavycraft\fly\Fly;
use wavycraft\fly\utils\Message;

class FlyCommand extends Command {

    private $plugin;
    public $fallProtection = [];

    public function __construct() {
        parent::__construct("fly", "Toggle flying");
        $this->plugin = Fly::getInstance();
        $this->setPermission("fly.cmd");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof Player) {
            $sender->sendMessage($this->plugin->getConfig()->get("player_only"));
            return true;
        }

        if (!$sender->hasPermission("fly.cmd")) {
            $sender->sendMessage($this->plugin->getConfig()->get("no_permission"));
            return true;
        }

        if (empty($args)) {
            $sender->sendMessage($this->plugin->getConfig()->get("usage"));
            return true;
        }

        $subcommand = strtolower($args[0]);
        $currentStatus = $sender->getAllowFlight();

        if ($subcommand === "on") {
            if ($currentStatus) {
                $sender->sendMessage($this->plugin->getConfig()->get("fly_already_on"));
            } else {
                $sender->setAllowFlight(true);
                $sender->sendMessage($this->plugin->getConfig()->get("fly_message_on"));
                Message::getInstance()->sendFlyTitle($sender, "fly_title_on", "fly_subtitle_on");
            }
        } elseif ($subcommand === "off") {
            if (!$currentStatus) {
                $sender->sendMessage($this->plugin->getConfig()->get("fly_already_off"));
            } else {
                if ($sender->isFlying()) {
                    $sender->setFlying(false);
                }
                $sender->setAllowFlight(false);

                $this->fallProtection[$sender->getName()] = $sender->getFallDistance();
                $sender->resetFallDistance();

                $sender->sendMessage($this->plugin->getConfig()->get("fly_message_off"));
                Message::getInstance()->sendFlyTitle($sender, "fly_title_off", "fly_subtitle_off");
            }
        } else {
            $sender->sendMessage($this->plugin->getConfig()->get("usage"));
        }

        return true;
    }

    public function getFallProtection(): array {
        return $this->fallProtection;
    }
}