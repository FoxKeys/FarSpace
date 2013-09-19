<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 19.09.2013 21:19
	 */

	class buildTasks extends activeRecordCollection {
		/**
		 * @param mixed $idPlayer
		 * @param mixed $idPlanet
		 * @return array
		 */
		protected function selectData( $idPlayer, $idPlanet = null ) {
			return game::DB()->select('
					SELECT	bt.idBuildTask,
							pt.idTech
					FROM	build_tasks bt
							INNER JOIN planets pln on bt.idPlanet = pln.idPlanet
							INNER JOIN players_techs pt ON bt.idPlayerTech = pt.idPlayerTech
					WHERE	pln.idPlayer = :idPlayer
							AND bt.idPlanet = :idPlanet',
				array(':idPlayer' => $idPlayer),
				array(':idPlanet' => $idPlanet)
			);
		}

		/**
		 * @param int $idPlayer
		 * @param int|null $idPlanet
		 * @return buildTask[]
		 */
		public function select( $idPlayer, $idPlanet = null ) {
			return parent::select( $idPlayer, $idPlanet );
		}

		/**
		 * @param array $data
		 * @return buildTask
		 */
		protected function createObject( $data ) {
			return buildTask::createFromArray( $data );
		}

	}