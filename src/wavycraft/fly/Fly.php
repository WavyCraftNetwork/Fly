<?php

declare(strict_types=1);

namespace wavycraft\fly;

use pocketmine\plugin\PluginBase;
use wavycraft\fly\command\FlyCommand;
use wavycraft\fly\event\EventListener;

class Fly extends PluginBase {

    private static $instance;

    protected function onLoad(): void {
        self::$instance = $this;
    }

    protected function onEnable(): void {
        $this->saveDefaultConfig();
        $flyCommand = new FlyCommand();
        $this->getServer()->getCommandMap()->register("Fly", $flyCommand);
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($flyCommand), $this);
    }

    public static function getInstance(): self {
        return self::$instance;
    }
}