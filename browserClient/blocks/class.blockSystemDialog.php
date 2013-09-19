<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 07.09.2013 10:36
	 */

class blockSystemDialog extends baseBlock { public function render( system $system, player $player, $idPlanet = null ) { ?>

<section id="system-dialog-<?= e::attr( $system->idSystem() ) ?>" class="system-dialog">
	<span class="images starClass<?= e::attr( substr( $system->idStarClass(), -1, 1 ) ) ?>">
		<?php foreach ( game::planets()->select( $player->idPlayer(), $system->idSystem() ) as $planet ) { ?>
			<img data-planet-id="tab-planet-<?= e::attr( $planet->idPlanet() ) ?>" class="planet<?=$planet->idPlanet() == $idPlanet ? ' active' : ''?>" src="/browserClient/img/system/planet_<?= e::attr( $planet->idPlanetType() . $planet->idPlanet() % config::$planetImgCnt[$planet->idPlanetType()] ) ?>.png"/>
		<?php } ?>
	</span>
	<div class="tab systemInfo" style="display: none">&nbsp;</div>
	<?php foreach ( game::planets()->select( $player->idPlayer(), $system->idSystem() ) as $planet ) { ?>
		<div id="tab-planet-<?= e::attr( $planet->idPlanet() )?>" class="tab planetInfo"<?=$planet->idPlanet() != $idPlanet ? ' style="display: none"' : ''?>>
			<div class="dataColumn left">
				<div class="planetData">
					<h3><?= t::__( 'Planet' ) ?>: <span class="planetName"><?= e::html( $planet->name() ) ?></span>: <span class="planetMorale"><?= e::levelValue( $planet->morale() ) ?></span></h3>
					<dl>
						<dt><?= t::__( 'Planet type' ) ?></dt>
						<dd><?= e::html( game::planetTypes()->get( $planet->idPlanetType() )->name() ) ?></dd>
						<dt><?= t::__( 'Diameter' ) ?></dt>
						<dd><?= e::levelValue( $planet->plDiameter() ) ?></dd>
						<dt><?= t::__( 'Environment' ) ?></dt>
						<dd><?= e::levelValue( $planet->plBio() ) ?></dd>
						<dt><?= t::__( 'Min. abundance' ) ?></dt>
						<dd><?= e::levelValue( $planet->plMin() ) ?></dd>
						<dt><?= t::__( 'En. abundance' ) ?></dt>
						<dd><?= e::levelValue( $planet->plEn() ) ?></dd>
						<dt><?= t::__( 'Available space' ) ?></dt>
						<dd><?= e::levelValue( $planet->plSlots() ) ?> / <?= e::levelValue( $planet->plMaxSlots() ) ?></dd>
					</dl>
				</div>
				<div class="colonyData">
					<h3><?= t::__( 'Colony data' ) ?></h3>
					<dl>
						<dt>Population</dt>
						<dd><?= e::levelValue( $planet->storPop() ) ?></dd>
						<dt>Pop. support</dt>
						<dd>&nbsp;</dd>
						<dt><?= t::__( 'Biomatter' ) ?></dt>
						<dd><?= e::levelValue( $planet->storBio() ) ?></dd>
						<dt>Min. reserve</dt>
						<dd>&nbsp;</dd>
						<dt>Energy</dt>
						<dd><?= e::levelValue( $planet->storEn() ) ?></dd>
						<dt>Min. reserve</dt>
						<dd>&nbsp;</dd>
						<dt>Scan level</dt>
						<dd><?= e::html( sprintf( '%.2f', $planet->level() ) ) ?></dd>
						<dt>Id planet</dt>
						<dd><?= (int)$planet->idPlanet() ?></dd>

					</dl>
				</div>
				<div class="systemData"></div>
			</div>
			<div class="dataColumn right">
				<div class="structures">
					<h3><?= t::__( 'Structures' ) ?></h3>
					<?php $structures = game::structures()->select( $player->idPlayer(), $planet->idPlanet() ); ?>
					<?php $ADDSLOT3 = game::playersTechs()->getNoException( null, $player->idPlayer(), config::TECH_ADDSLOT3 ) ?>
					<?php for ( $i = 0; $i < ( !empty( $ADDSLOT3 ) ? $planet->plMaxSlots() : $planet->plSlots() ); $i++ ) { ?>
						<?php $structure = isset( $structures[$i] ) ? $structures[$i] : null; ?>
						<img<?= !empty( $structure ) ? ' data-f-id-structure="' . (int)$structure->idStructure() . '"' : '' ?> class="slot" src="/browserClient/img/techs/<?= !empty( $structure ) ? (int)$structure->idTech() : ( $i >= $planet->plSlots() ? sprintf( '%.4d', config::TECH_ADDSLOT3 ) : '0001' ) ?>.png"/>
					<?php } ?>
				</div>
				<div class="task-queue">
					<h3><?= t::__( 'Task queue' ) ?></h3>
					<?php $tasks = game::buildTasks()->select( $player->idPlayer(), $planet->idPlanet() ); ?>
					<?php foreach ( $tasks as $task ) { ?>
						<img class="task" data-f-id-build-task="<?= (int)$task->idBuildTask() ?>" src="/browserClient/img/techs/<?= (int)$task->idTech() ?>.png"/>
					<?php } ?>
					<?php if ( count( $tasks ) < 20 ) { ?>
						<img class="slot" class="task" src="/browserClient/img/techs/0003.png"/>
					<?php } ?>
				</div>
				<div class="planet-info">
					<h3><?= t::__( 'Planet Upgrade / Downgrade data' ) ?></h3>
				</div>
				<?php foreach ( $structures as $structure ) { ?>
					<div class="structure-info" id="structure-info-<?= $structure->idStructure() ?>" style="display: none">
						<h3><?= t::__( 'Structure info' ) ?>: <?= $structure->idStructure() ?></h3>
					</div>
				<?php } ?>
				<?php foreach ( $tasks as $task ) { ?>
					<div class="task-info" id="task-info-<?= $task->idBuildTask() ?>" style="display: none">
						<h3><?= t::__( 'Task info' ) ?>: <?= $task->idBuildTask() ?></h3>
					</div>
				<?php } ?>
			</div>
		</div>
	<?php } ?>
</section>
<script>
	jQuery(function ($) {
		var $dialog = $("#system-dialog-<?= $system->idSystem() ?>").dialog({
			autoOpen: true,
			modal: true,
			title: "<?=e::attr( sprintf('System: %s [%.2f, %.2f]', $system->name(), $system->x(), $system->y()) )?>",
			width: 800,
			height: 600,
			position: 'center',
			close: function (event, ui) {
				$dialog.dialog("destroy").remove();
			},
			buttons: {
				'Close': function () {
					$dialog.dialog('close');
					return false;
				}
			}
		});
		$dialog.on('click.FS', '.planet', function(){
			$('.planetInfo', $dialog).hide();
			$('.systemInfo', $dialog).hide();
			$('#' + $(this).data('planet-id')).show();
			$(this).siblings('.planet').removeClass('active');
			$(this).addClass('active');
			return false;
		});
		$dialog.on('click.FS', '.images', function () {
			$('.planetInfo', $dialog).hide();
			$('.systemInfo', $dialog).show();
			$('.planet', this).removeClass('active');
		});
		$dialog.on('click.FS', '.structures .slot, .task-queue .task', function () {
			$('.slot, .task', $dialog).removeClass('active');
			$(this).addClass('active');
			$('.planet-info').hide();
			$('.structure-info').hide();
			$('.task-info').hide();
			if ($(this).hasClass('task')) {
				$('#task-info-' + $(this).data('f-id-build-task')).show();
			}
			if ($(this).hasClass('slot')) {
				$('#structure-info-' + $(this).data('f-id-structure')).show();
			}
		});
	})
</script>

<?php }	}