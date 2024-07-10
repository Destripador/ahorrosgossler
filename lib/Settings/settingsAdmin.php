<?php
namespace OCA\AhorrosGossler\Settings;

use OCP\AppFramework\Http\TemplateResponse;
use OCP\IConfig;
use OCP\IL10N;
use OCP\Settings\ISettings;

# dependencias agregadas
use OCP\IUserManager; // USUARIOS DE LA Nube
use OCA\AhorrosGossler\Db\userahorroMapper; // Modelo usuario_ahorros

class settingsAdmin implements ISettings {
    private IL10N $l;
    private IConfig $config;
	private $userManager;
	private $userahorroMapper;

    public function __construct(IConfig $config, IL10N $l, IUserManager $userManager, userahorroMapper $userahorroMapper /* se agrega mapeador al constructor*/) {
        $this->config = $config;
        $this->l = $l;
		$this->userManager = $userManager;
		$this->userahorroMapper = $userahorroMapper; //se declara
    }

    /**
     * @return TemplateResponse
     */
    public function getForm() {
        # Obtener listado de usuarios con user manager y search
        $users = $this->userahorroMapper->getAllUsers();
        $UsersWithAhorro = $this->userahorroMapper->getUsersWithAhorro();

        $parameters = [
            'mySetting' => $this->config->getSystemValue('Ahorro Gossler', true),
            'Users' => $users,
            'UsersWithAhorro' => $UsersWithAhorro,
        ];

        return new TemplateResponse('ahorrosgossler', 'Settings/admin', $parameters, '');
    }

    public function getSection() {
        return 'ahorrosgossler'; // Name of the previously created section.
    }

    public function getPriority() {
        return 10;
    }
}

