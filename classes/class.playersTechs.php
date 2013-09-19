<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 18.09.2013 23:49
	 */

	class playersTechs extends activeRecordCollection {
		/**
		 * @param null $idPlayerTech
		 * @param null $idPlayer
		 * @param null $idTech
		 * @internal param null $idPlanetType
		 * @return array
		 */
		protected function selectData( $idPlayerTech = null, $idPlayer = null, $idTech = null ) {
			return game::DB()->select( '
				SELECT	*
				FROM	players_techs
				WHERE	(:idPlayerTech IS NULL OR idPlayerTech = :idPlayerTech)
						AND (:idPlayer IS NULL OR idPlayer = :idPlayer)
						AND (:idTech IS NULL OR idTech = :idTech)',
				array( ':idPlayerTech' => $idPlayerTech ),
				array( ':idPlayer' => $idPlayer ),
				array( ':idTech' => $idTech )
			);
		}

		/**
		 * @param array $data
		 * @return playerTech
		 */
		protected function createObject( $data ) {
			return playerTech::createFromArray( $data );
		}

		/**
		 * @param null $idPlayerTech
		 * @param null $idPlayer
		 * @param null $idTech
		 * @internal param null $idPlanetType
		 * @return playerTech|null
		 */
		public function getNoException( $idPlayerTech = null, $idPlayer = null, $idTech = null ) {
			return parent::getNoException( $idPlayerTech, $idPlayer, $idTech );
		}

	}