<?php

declare(strict_types=1);

namespace OCA\AhorrosGossler\Db;

use OCP\AppFramework\Db\Entity;


class userahorro extends Entity {
    
    protected string $idUser = '';
    protected string $idPermision = '';
    protected string $state = '';
    protected string $cantidad = '';
    protected string $lastModified = '';

	public function __construct() {
        $this->addType('id_user', 'string');
		$this->addType('id_permision', 'string');
		$this->addType('state', 'string');
		$this->addType('cantidad', 'string');
		$this->addType('last_modified', 'integer');
	}

	public function read(): array {
		return [
			'id' => $this->id,
			'id_user' => $this->idUser,
			'id_permision' => $this->idPermision,
			'state' => $this->state,
			'cantidad' => $this->cantidad,
			'last_modified' => (int) $this->lastModified,
		];
	}
}