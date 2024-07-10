<?php
namespace OCA\AhorrosGossler\AppInfo;

use OCA\Files\Event\LoadAdditionalScriptsEvent;
use OCA\AhorrosGossler\Listener\LoadAdditionalListener;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;

/**
 * @package OCA\AhorrosGossler\AppInfo
 */
class Application extends App implements IBootstrap {
	public const APP_ID = 'ahorrosgossler';
  
	public function __construct(array $urlParams = []) {
	  parent::__construct(self::APP_ID, $urlParams);
	}
  
	public function register(IRegistrationContext $context): void {
	  $context->registerEventListener(LoadAdditionalScriptsEvent::class, LoadAdditionalListener::class);
	}
  
	public function boot(IBootContext $context): void {}
}