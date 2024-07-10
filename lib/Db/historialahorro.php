<?php

declare(strict_types=1);

namespace OCA\AhorrosGossler\Db;

use OCP\AppFramework\Db\Entity;


class historialahorro extends Entity {
    
    protected string $idUser = '';
    protected string $cantidSolicitada = '';
    protected string $fechaSolicitud = '';
    protected string $estado = '';
    protected string $nota = '';

	public function __construct() {
        $this->addType('id_user', 'string');
		$this->addType('cantidad_solicitada', 'string');
		$this->addType('fecha_solicitud', 'string');
		$this->addType('estado', 'string');
		$this->addType('nota', 'string');
	}

	public function read(): array {
		return [
			'id_historial' => $this->id,
			'id_user' => $this->idUser,
			'cantidad_solicitada' => $this->cantidSolicitada,
			'fecha_solicitud' => $this->fechaSolicitud,
			'estado' => $this->estado,
			'nota' => $this->nota,
		];
	}
}