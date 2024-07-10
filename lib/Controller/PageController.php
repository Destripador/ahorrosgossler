<?php
declare(strict_types=1);
// SPDX-FileCopyrightText: Luis Angel Alvarado Hernandez <luis.alvarado@crowe.mx>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\AhorrosGossler\Controller;

use OCA\AhorrosGossler\AppInfo\Application;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IRequest;
use OCP\Util;
use OCP\AppFramework\Http\Response;
use DateTime;
use DateTimeZone;

#dependencias agregadas
use OCP\IUserSession;
use OCP\IUserManager;


use OCA\AhorrosGossler\Db\userahorroMapper;
use OCA\AhorrosGossler\Db\userahorro;

use OCA\AhorrosGossler\Db\historialahorroMapper;
use OCA\AhorrosGossler\Db\historialahorro;

require_once 'SimpleXLSXGen.php';
use Shuchkin\SimpleXLSXGen;

class PageController extends Controller {

	private $userSession;
	private $userManager;
	private $userahorroMapper;
	private $historialahorroMapper;

	public function __construct(IRequest $request, IUserSession $userSession, IUserManager $userManager, userahorroMapper $userahorroMapper, historialahorroMapper $historialahorroMapper) {
		parent::__construct(Application::APP_ID, $request);

		$this->userSession = $userSession;
		$this->userManager = $userManager;
		$this->userahorroMapper = $userahorroMapper;
		$this->historialahorroMapper = $historialahorroMapper;
		
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function index(): TemplateResponse {
		Util::addScript(Application::APP_ID, 'ahorrosgossler-main');
		return new TemplateResponse(Application::APP_ID, 'main');
	}
	
	/**
	* @NoAdminRequired
	* @NoCSRFRequired
	*/
	public function historial(): TemplateResponse {
		Util::addScript(Application::APP_ID, 'ahorrosgossler-historial');
		return new TemplateResponse(Application::APP_ID, 'historial');
	}

	/**
	* @NoAdminRequired
	* @NoCSRFRequired
	*/
	public function solicitar(): TemplateResponse {
		Util::addScript(Application::APP_ID, 'ahorrosgossler-solicitar');
		return new TemplateResponse(Application::APP_ID, 'solicitar');
	}

	/**
	* @NoAdminRequired
	* @NoCSRFRequired
	*/
	public function panel(): TemplateResponse {
		Util::addScript(Application::APP_ID, 'ahorrosgossler-panel');
		return new TemplateResponse(Application::APP_ID, 'panel');
	}

	/**
	* @NoAdminRequired
	* @NoCSRFRequired
	*/
	public function MostrarDetalle(): TemplateResponse {
		Util::addScript(Application::APP_ID, 'ahorrosgossler-MostrarDetalle');
		return new TemplateResponse(Application::APP_ID, 'panel');
	}
	
	/**
	* @NoAdminRequired
	* @NoCSRFRequired
	*/
	public function getSolicitudId(string $id) : array {
		//getahorrobyid
		try{
			$data = $this->historialahorroMapper->getSolicitudId($id);
			
			return $data; 
		}
		catch(Exception $e){
			return $e;
		}

	}

	/**
	* @NoAdminRequired
	* @NoCSRFRequired
	*/
	public function getHistorial(string $id_user) : array {
		//getahorrobyid
		try{
			$user = $this->historialahorroMapper->getahorrobyid($id_user);
			
			return $user; 
		}
		catch(Exception $e){
			return $e;
		}

	}

	/**
	* @NoAdminRequired
	* @NoCSRFRequired
	*/
	public function GetHistorialPanel(string $options_fechas_value, string $options_estado_values) : array {
		try{
			$user = $this->historialahorroMapper->GetHistorialPanel($options_fechas_value, $options_estado_values);
			
			return $user; 
		}
		catch(Exception $e){
			return $e;
		}

	}

	/**
	* @NoAdminRequired
	* @NoCSRFRequired
	*/
	public function getUsersAhorros(): array{
		$UsersWithAhorro = $this->userahorroMapper->getUsersWithAhorro();
		$Users = $this->userahorroMapper->getAllUsers();
		
		$data = array(
			'UsersWithAhorro' => $UsersWithAhorro,
			'Users' => $Users,
        );

		return $data;
	}

	/**
	* @NoAdminRequired
	* @NoCSRFRequired
	*/
	public function addUser(string $id_user, string $id_permision, string $state, string $cantidad): string {
		try{
			$timestamp = (new DateTime())->getTimestamp();

			$user = new userahorro();
			$user->setidUser($id_user);
			$user->setidPermision($id_permision);
			$user->setstate($state);
			$user->setcantidad($cantidad);
			$user->setlastModified($timestamp);
			$this->userahorroMapper->insert($user);
			
			return "ok"; 
		}
		catch(Exception $e){
			return $e;
		}
	}

	/**
	* @NoAdminRequired
	* @NoCSRFRequired
	*/
	public function updateUser(int $id, string $id_permision, string $state, string $cantidad): string {
		try{
			$this->userahorroMapper->updateUserId($id, $id_permision, $state, $cantidad);
			
			return "ok"; 
		}
		catch(Exception $e){
			return $e;
		}
	}

	/**
	* @NoAdminRequired
	* @NoCSRFRequired
	*/
	public function userbyid(string $id_user): array{
		try{
			$user = $this->userahorroMapper->userbyid($id_user);
			
			return $user; 
		}
		catch(Exception $e){
			return $e;
		}
	}

	/**
	* @NoAdminRequired
	* @NoCSRFRequired
	*/
	public function EnviarSolicitud(int $id, string $id_user, float $cantidad_solicitada, string $nota, string $state, string $cantidad_total): string {
		try{
			$this->historialahorroMapper->EnviarSolicitud($id_user, $cantidad_solicitada, $nota, $cantidad_total);
			$this->userahorroMapper->updatePermisionUserId($id, $state);
			
			
			return "ok"; 
		}
		catch(Exception $e){
			return $e;
		}
	}

	/**
	* @NoAdminRequired
	* @NoCSRFRequired
	*/
	public function GenerateReport(string $options_fechas_value, string $options_estado_values): string {
		try{
			
			$user = $this->historialahorroMapper->GetHistorialPanel($options_fechas_value, $options_estado_values);
			$books = [["ID", 'NOMBRE', 'CORREO', 'CANTIDAD SOLICITADA', "ESTADO"]];

			foreach($user as $datas){
				if($datas['estado'] == 0){
					$estado = "Pendiente";
				}
				else{
					$estado = "Aprobado";
				}

				array_push($books, 
					[$datas['id'], 
					$datas['displayname'], 
					json_decode($datas['data'], true)['email']['value'],
					'$' . $datas['cantidad_solicitada'] . "", 
					$estado]);
			}

			
		
			$xlsx = \Shuchkin\SimpleXLSXGen::fromArray( $books );
			//$xlsx->saveAs('books.xlsx'); // or downloadAs('books.xlsx') or $xlsx_content = (string) $xlsx 
		
			$fileContent = $xlsx->downloadAs('php://memory');

			return $books; 
		}
		catch(Exception $e){
			return $e;
		}
	}
	
	/**
	* @NoAdminRequired
	* @NoCSRFRequired
	*/
	public function AceptarAhorro(int $id_ahorro, int $id): string {
		try{
			$this->historialahorroMapper->AceptarAhorro($id_ahorro);	
			$this->userahorroMapper->updatePermisionUserId($id, '0');	
			
			return "ok"; 
		}
		catch(Exception $e){
			return $e;
		}
	}

	/**
	* @NoAdminRequired
	* @NoCSRFRequired
	*/
	public function DenegarAhorro(int $id_ahorro, int $id): string {
		try{
			$this->historialahorroMapper->DenegarAhorro($id_ahorro);		
			$this->userahorroMapper->updatePermisionUserId($id, '1');	
			
			return "ok"; 
		}
		catch(Exception $e){
			return $e;
		}
	}
}
