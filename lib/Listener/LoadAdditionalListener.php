<?php
declare(strict_types=1);
// SPDX-FileCopyrightText: Luis Angel Alvarado Hernandez <luis.alvarado@crowe.mx>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\AhorrosGossler\Listener;

use OCA\AhorrosGossler\AppInfo\Application;
use OCA\Files\Event\LoadAdditionalScriptsEvent;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\Util;

class LoadAdditionalListener implements IEventListener {

    public function handle(Event $event): void {
        if (!($event instanceof LoadAdditionalScriptsEvent)) {
            return;
        }

        Util::addScript(Application::APP_ID, 'main');
    }
}