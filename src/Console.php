<?php
namespace Phpnova\Rest;

use Composer\Script\Event;

class Console
{
    public static function execute(Event $event): void
    {
        $args = $event->getArguments();
        echo json_encode($event->getFlags(), 128);
    }
}