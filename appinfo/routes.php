<?php
declare(strict_types=1);
// SPDX-FileCopyrightText: Luis Angel Alvarado Hernandez <luis.alvarado@crowe.mx>
// SPDX-License-Identifier: AGPL-3.0-or-later

/**
 * Create your routes in here. The name is the lowercase name of the controller
 * without the controller part, the stuff after the hash is the method.
 * e.g. page#index -> OCA\AhorrosGossler\Controller\PageController->index()
 *
 * The controller class has to be registered in the application.php file since
 * it's instantiated in there
 */
return [

	'routes' => [
		['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],
		['name' => 'page#historial', 'url' => '/historial', 'verb' => 'GET'],
		['name' => 'page#solicitar', 'url' => '/solicitar', 'verb' => 'GET'],
		['name' => 'page#panel', 'url' => '/panel', 'verb' => 'GET'],
		['name' => 'page#MostrarDetalle', 'url' => '/MostrarDetalle/{id_detalle}', 'verb' => 'GET'],
		
		['name' => 'page#test', 'url' => '/geti', 'verb' => 'GET'],
		['name' => 'page#getUsersAhorros', 'url' => '/getUsersAhorros', 'verb' => 'GET'],
		['name' => 'page#addUser', 'url' => '/addUser', 'verb' => 'POST'],
		['name' => 'page#updateUser', 'url' => '/updateUser', 'verb' => 'POST'],
		['name' => 'page#userbyid', 'url' => '/userbyid', 'verb' => 'POST'],
		['name' => 'page#getHistorial', 'url' => '/getHistorial/{id_user}', 'verb' => 'GET'],
		['name' => 'page#EnviarSolicitud', 'url' => '/EnviarSolicitud', 'verb' => 'POST'],
		['name' => 'page#GetHistorialPanel', 'url' => '/GetHistorialPanel/{options_fechas_value}/{options_estado_values}', 'verb' => 'GET'],
		['name' => 'page#GenerateReport', 'url' => '/GenerateReport/{options_fechas_value}/{options_estado_values}', 'verb' => 'GET'],
		['name' => 'page#getSolicitudId', 'url' => '/getSolicitudId/{id}', 'verb' => 'GET'],
		
		['name' => 'page#AceptarAhorro', 'url' => '/AceptarAhorro', 'verb' => 'POST'],
		['name' => 'page#DenegarAhorro', 'url' => '/DenegarAhorro', 'verb' => 'POST'],
	],
	
];
