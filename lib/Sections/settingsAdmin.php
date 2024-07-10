<?php
namespace OCA\AhorrosGossler\Sections;

use OCP\IL10N;
use OCP\IURLGenerator;
use OCP\Settings\IIconSection;

class settingsAdmin implements IIconSection {
    private IL10N $l;
    private IURLGenerator $urlGenerator;

    public function __construct(IL10N $l, IURLGenerator $urlGenerator) {
        $this->l = $l;
        $this->urlGenerator = $urlGenerator;
    }

    public function getIcon(): string {
        return $this->urlGenerator->imagePath( 'ahorrosgossler' , 'crowe-pig-dark.png');
    }

    public function getID(): string {
        return 'ahorrosgossler';
    }

    public function getName(): string {
        return $this->l->t('Ahorro Gossler');
    }

    public function getPriority(): int {
        return 11;
    }
}