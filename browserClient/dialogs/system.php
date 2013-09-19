<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 31.05.2013 19:36
	 */
?>
<section id="system-dialog-template" style="display: none">
	<div class="system-dialog">
		<span class="images"></span>
		<div class="dataColumn left">
			<div class="planetData">
				<h3>Planet: <span class="planetName"></span><span class="planetMorale">&nbsp; <?php //ToDo ?></span></h3>
				<dl>
					<dt>Planet type</dt><dd class="namePlanetType">&nbsp;</dd>
					<dt>Diameter</dt><dd class="diameter">&nbsp;</dd>
					<dt>Environment</dt><dd class="">&nbsp;</dd>
					<dt>Min. abundance</dt><dd class="plMin">&nbsp;</dd>
					<dt>En. abundance</dt><dd class="plEn">&nbsp;</dd>
					<dt>Available space</dt><dd class="slots">&nbsp;</dd>
				</dl>
			</div>
			<div class="colonyData">
				<h3>Colony data</h3>
				<dl>
					<dt>Population</dt><dd class="storPop">&nbsp;</dd>
					<dt>Pop. support</dt><dd class="popSupport">&nbsp;</dd>
					<dt>Biomatter</dt><dd class="plBio">&nbsp;</dd>
					<dt>Min. reserve</dt><dd class="">&nbsp;</dd>
					<dt>Energy</dt><dd class="">&nbsp;</dd>
					<dt>Min. reserve</dt><dd class="">&nbsp;</dd>
				</dl>
			</div>
			<div class="systemData"></div>
		</div>
		<div class="dataColumn right">
			<div class="structures">
				<h3>Structures</h3>
			</div>
		</div>
	</div>
</section>
<script>
	jQuery(function ($) {
		var planetImgCnt = {'A': 2, 'C': 2, 'D': 5, 'E': 3, 'G': 9, 'H': 4, 'I': 3, 'M': 1, 'R': 5, 'X': 1};
		$(document).on('show.systemDialog.FS', function (event, system, planet ) {
			var $dialog =  $("#system-dialog-template").find(".system-dialog").clone().appendTo('body').dialog({
				autoOpen: false,
				modal: true,
				title: "System:",
				width: 800,
				height: 600,
				position: 'center',
				close: function( event, ui ) {
					$dialog.dialog( "destroy").remove();
				},
				buttons: {
					'Close': function () {
						$dialog.dialog('close');
						return false;
					}
				}
			});

			$('.images', $dialog).addClass('starClass' + system.starClass);
			$.each(system.planets, function (index, sysPlanet) {
				var $planet = $('<img class="planet" src="/browserClient/img/system/planet_' + sysPlanet.idPlanetType + sysPlanet.idPlanet % planetImgCnt[sysPlanet.idPlanetType] + '.png"/>');
				$planet.data('planetInfo', sysPlanet);
				if (planet && sysPlanet.idPlanet == planet.idPlanet) {
					$planet.addClass('selected');
					selectPlanet(sysPlanet);
				}
				$('.images', $dialog).append($planet);
			});

			function selectPlanet(planetInfo){
				$.each(planetInfo, function(name, value){
					$('.' + name, $dialog).html(value);
				});
				$('.planetName', $dialog).html(system.name + ' ' + planetInfo.idPlanet);
				$('.diameter', $dialog).html(planetInfo.plDiameter ? planetInfo.plDiameter : '?');
				$('.plMin', $dialog).html(planetInfo.plMin ? planetInfo.plMin : '?');
				$('.slots', $dialog).html(planetInfo.plSlots + ' / ' + planetInfo.plMaxSlots);
			}

			$dialog.on('click.FS', '.planet', function(){
				$(this).siblings('.planet').removeClass('selected');
				$(this).addClass('selected');
				selectPlanet($(this).data('planetInfo'));
			});
			$dialog.dialog( 'open' );
			console.log('show.systemDialog.FS', system, planet);
		});
		//f.setupAjaxSubmit($('form', '#login-dialog'), {}, {});
	})
</script>