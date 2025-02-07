<?php

declare(strict_types=1);

/**
 * @copyright Copyright (c) 2024 Your name <your@email.com>
 *
 * @author Your name <your@email.com>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\AhorrosGossler\Migration;

use Closure;
use OCP\DB\ISchemaWrapper;
use OCP\Migration\IOutput;
use OCP\Migration\SimpleMigrationStep;

/**
 * Auto-generated migration step: Please modify to your needs!
 */
class Version1Date20240401195029 extends SimpleMigrationStep {

	/**
	 * @param IOutput $output
	 * @param Closure(): ISchemaWrapper $schemaClosure
	 * @param array $options
	 */
	public function preSchemaChange(IOutput $output, Closure $schemaClosure, array $options): void {
	}

	/**
	 * @param IOutput $output
	 * @param Closure(): ISchemaWrapper $schemaClosure
	 * @param array $options
	 * @return null|ISchemaWrapper
	 */
	public function changeSchema(IOutput $output, Closure $schemaClosure, array $options): ?ISchemaWrapper {
		
  		/** @var ISchemaWrapper $schema */
  		$schema = $schemaClosure();
  
  		if (!$schema->hasTable('historial_ahorro')) {
  			$table = $schema->createTable('historial_ahorro');
  			$table->addColumn('id_historial', 'integer', [
  				'autoincrement' => true,
  				'notnull' => true,
  			]);
  			$table->addColumn('id_user', 'string', [
  			]);
  			$table->addColumn('cantidad_solicitada', 'string', [
  			]);
			$table->addColumn('cantidad_total', 'string', [
			]);
  			$table->addColumn('fecha_solicitud', 'string', [
  			]);
  			$table->addColumn('estado', 'string', [
  			]);
			  $table->addColumn('nota', 'string', [
			]);
  			$table->setPrimaryKey(['id_historial']);
  		}
  
  		return $schema;

	}

	/**
	 * @param IOutput $output
	 * @param Closure(): ISchemaWrapper $schemaClosure
	 * @param array $options
	 */
	public function postSchemaChange(IOutput $output, Closure $schemaClosure, array $options): void {
	}
}
