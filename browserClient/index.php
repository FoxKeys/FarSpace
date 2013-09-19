<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 10.02.2013 7:33
	 */
	//Register core scripts
	JS::registerJS( config::$coreJSArray );
	JS::addToHeader('kinetic');
	JS::addToHeader('jquery.ui');
	JS::addToHeader('jquery.mousewheel');
	JS::addToHeader('f');
	JS::addToHeader('f.messages');
	JS::addToHeader('f.imageCache');
	JS::addToHeader('f.grid');
	JS::addToHeader('f.scanner');
	JS::addToHeader('f.planetRenderer');
	JS::addToHeader('f.systemRenderer');
	JS::addToHeader('f.system');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>FarSpace 0.1</title>
    <meta name="description" content="The FarSpace game">
    <meta name="author" content="Sycoder">
	<link rel="stylesheet" href="/browserClient/css/reset.css">
    <link rel="stylesheet" href="/browserClient/css/style.css">
	<link rel="stylesheet" href="/browserClient/css/redmond/jquery-ui-1.10.0.custom.css">

	<style>

		#container {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
		}
		#hint {
			position: absolute;
			background-color: #313A5F;
			color: #F2F2F5;
			padding: 5px;
			font-size: 12px;
			border-radius: 5px;
		}
		#hint strong{
			color: #FFD800;
		}
		#hint p{
			line-height: 1.2;
		}
	</style>
	<?=JS::getSettings()?>
	<?=JS::getHeaderScripts(); ?>

	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	 <![endif]-->
<?php /*
	<script src="js/jquery.validate.js"></script>
	<script src="js/jquery.form.js"></script>
	<!--<script src="js/main.js"></script>-->
	*/ ?>
</head>
<body id="farSpace">
	<?php //require_once( 'dialogs/login.php' );?>
	<?php require_once( 'dialogs/system.php' );?>
	<div id="container"></div>
	<div id="hint"></div>
	<script>
		jQuery(function($){

			const	MIN_SCALE = 10,
					MAX_SCALE = 600;
            var		scale = farSpace.FSConst.defaultScale,
                    $container = $('#container'),
                    container = $container[0],
					$hint = $('#hint'),
                    scanners = [],
                    systems = [],
					fleets = [],
                    starImages = {},
					stratResImages = {};

			var stage = new Kinetic.Stage({
				container: 'container',
				width: $container.width(),
				height: $container.height()
			});
			$(stage.getContent()).css('position', 'absolute');

			var shapesLayer = new Kinetic.Layer({clearBeforeDraw: false});
			var context = shapesLayer.getContext();
			var imageCache = new TImageCache();
			var grid = new TGrid();
			var scanner = new TScanner();
			var planetRenderer = new TPlanetRenderer();
			var systemRenderer = new TSystemRenderer();
			//context.textBaseline = 'top';//ToDo
			// coordinates
			var currX = 15;
			var currY = -20;
			var rect = {top: 0, left:0, right: stage.getWidth(), width: stage.getWidth(), height: stage.getHeight(), bottom: stage.getHeight()};
			var maxX = rect.width;
			var maxY = rect.height;
			var centerX = Math.round(rect.width / 2);
			var centerY = Math.round(rect.height / 2);
			var showScanners = true;
			var toggleControlAreas = false;
			var overlayMode = farSpace.FSConst.OVERLAY_OWNER;

			// add the layer to the stage
			stage.add(shapesLayer);
			shapesLayer.beforeDraw(function(){
				//background
				context.fillStyle = "#000";
				context.fillRect(0, 0, rect.width, rect.height);

				//# scanners
				//# scanner ranges and control areas
				if (showScanners || toggleControlAreas) {
					if (toggleControlAreas) {
						console.log('ToDo: drawControlAreas');
						//self.drawControlAreas();
					}
					else {
						scanner.draw(scanners, context, rect, currX, currY, scale, centerX, centerY);
					}
				}

				grid.draw(context, rect, currX, currY, scale, centerX, centerY);
			});

			// элемент #myelement обрабатывает события прокрутка колесика мышки
            $container.mousewheel(function (event, delta) {
                var step = Math.max(Math.round(scale / 10), 1);
                scale += step * delta;
                scale = Math.max(MIN_SCALE, scale);
                scale = Math.min(scale, MAX_SCALE);
                paint();
				return false;
            });

            function cInt(value) {
                return parseInt(value, 10);
            }

			//console.log(res.getFFColorCode(REL_UNDEF));
			function paint(){
				shapesLayer.removeChildren();
				//if (canvas && canvas.getContext) {
					var self = {
						showPirateAreas: false,
						showRedirects: false,
						showGateSystems: false,
						showGateNetworks: false,
						showSystems: true,
						showPlanets: true,
						showFleets: true,
						showFleetLines: true,
						showCivilianFleets: true,
                        overlayColorColumns: {}
					};

/*					var fleetPainter = {
						minFleetSymbolSize: 4,
						maxFleetSymbolSize: 22,
						draw: function () {
							//# coordinates
							//minSize = int(gdata.config.defaults.minfleetsymbolsize)
							var rectSize = Math.max(fleetPainter.minFleetSymbolSize, Math.floor(scale / 7) - Math.floor(scale / 7) % 2);
							if (fleetPainter.maxFleetSymbolSize != 0) {
								rectSize = Math.min(fleetPainter.maxFleetSymbolSize, rectSize);
							}
							var rectSpace = rectSize + Math.max(1, Math.round(rectSize / 5));
							//# draw orders lines
							if (self.showFleetLines && fleets) {
								context.lineWidth = 1;
								$.each(fleets, function (index, fleet) {//for x1, y1, x2, y2, color, military in self._map[self.MAP_FORDERS]:
									if(fleet.currentOrder){
										if (!self.showCivilianFleets && !fleet.military) {
											return true;//Go to next fleet
										}
										var sx1 = cInt((fleet.currentOrder.x1 - currX) * scale) + centerX;
										sy1 = maxY - (cInt((fleet.currentOrder.y1 - currY) * scale) + centerY);
										sx2 = cInt((fleet.currentOrder.x2 - currX) * scale) + centerX;
										sy2 = maxY - (cInt((fleet.currentOrder.y2 - currY) * scale) + centerY);
										context.strokeStyle = '#FF0000';//ToDo!
										context.beginPath();
										context.moveTo(sx1, sy1);
										context.lineTo(sx2, sy2);
										context.stroke();
									}
									return true;
								});
							}
							/*# draw fleet symbol
							for objID, x, y, oldX, oldY, orbit, eta, color, size, military in self._map[self.MAP_FLEETS]:
								if not self.showCivilianFleets and not military:
									continue
								if overlayMode != gdata.OVERLAY_OWNER:
									color = res.fadeColor(color)
								sx = int((x - currX) * scale) + centerX
								sy = maxY - (int((y - currY) * scale) + centerY)
								if orbit >= 0 and scale >= 30:
									actRect = Rect(sx + (orbit % 7) * rectSpace + 13 + 2 * (orbit % 7), sy + scale/6 * (orbit / 7) + 6, rectSize, rectSize)
									# TODO this is a workaround - fix it when pygame gets fixed
									# pygame.draw.polygon(self._mapSurf, color,
									#	(actRect.midleft, actRect.midtop, actRect.midright, actRect.midbottom), 1)
									pygame.draw.polygon(self._mapSurf, color,
										(actRect.midleft, actRect.midtop, actRect.midright, actRect.midbottom), 0)
									actRect.move_ip(self.rect.left, self.rect.top)
									self._actAreas[objID] = actRect
								elif orbit < 0:
									rectSizeFlying = rectSize+2
									sox = int((oldX - currX) * scale) + centerX
									soy = maxY - (int((oldY - currY) * scale) + centerY)
									actRect = Rect(sx - rectSizeFlying / 2, sy - rectSizeFlying / 2, rectSizeFlying , rectSizeFlying)
									if military:
										mColor = color
									else:
										mColor = (0xff, 0xff, 0xff)
									pygame.draw.line(self._mapSurf, mColor, (sx, sy), (sox, soy), size + 1)
									# TODO rotate triangle
									pygame.draw.polygon(self._mapSurf, color,
										(actRect.midleft, actRect.midtop, actRect.midright, actRect.midbottom), 1)
									pygame.draw.polygon(self._mapSurf, color,
										(actRect.midleft, actRect.midtop, actRect.midright, actRect.midbottom), 0)
									if eta and scale > 15:
										img = renderText('small', eta, 1, color)
										self._mapSurf.blit(img, actRect.topright)
							*/
/*						}
					};*/

                    //# pirate area
                    if (self.showPirateAreas) {
                        console.log('ToDo: showPirateAreas');
                    }
/*					//# redirections
                    if (self.showRedirects) {
                        console.log('ToDo: drawRedirects');
                        //self.drawRedirects();
                    }
					//# gate systems
					if( self.showGateSystems){
						console.log('ToDo: showGateSystems');
						//self.drawGateSystems();
					}
					//# gate networks
					if(self.showGateNetworks){
						console.log('ToDo: showGateNetworks');
						/*if( self.lockObj){
                            self.drawGateNetworks(self.showGateNetworks, self.lockObj)
						}else{
							self.drawGateNetworks(self.showGateNetworks)
						}*/
/*					}*/
					//# stars
                    if (self.showSystems) {
                        systemRenderer.draw(systems, planetRenderer, overlayMode, context, shapesLayer, rect, currX, currY, scale, centerX, centerY);
                    }
/*					//# fleets
					if (self.showFleets) {
						fleetPainter.draw();
					}*/
					/*//# clean up flag
					self.repaintHotbuttons = 1
					self.repaintMap = 0
					*/
				//}
				shapesLayer.draw();
			}

			paint();

			$.ajax({
				url: "/ajax.php",
				type: 'POST',
				data: {action: 'scannerGetMap'}
			}).done(function (data) {
					console.log(data);
					scanners = data.data.scanners;
					systems = data.data.systems;
					//Preload images
					$.each(systems, function (index, system) {
						system.icons = [];
						system.image = imageCache.get('/browserClient/img/systems/star_' + system.starClass + '.png');
						if (system.refuelMax > 0) {
							if (system.refuelMax >= 87) {
								system.icons.push(imageCache.get('/browserClient/img/icons/fuel_99.png'));
							} else if (system.refuelMax >= 62) {
								system.icons.push(imageCache.get('/browserClient/img/icons/fuel_75.png'));
							} else if (system.refuelMax >= 37) {
								system.icons.push(imageCache.get('/browserClient/img/icons/fuel_50.png'));
							} else if (system.refuelMax >= 12) {
								system.icons.push(imageCache.get('/browserClient/img/icons/fuel_25.png'));
							}
						} else if (system.hasRefuel) {
							system.icons.push(imageCache.get('/browserClient/img/icons/fuel_-.png'));
						}
						if (system.planets) {
							$.each(system.planets, function (index, planet) {
								if (planet.idStratRes) {
									system.icons.push(imageCache.get('/browserClient/img/icons/sr_' + planet.idStratRes + '.png'));
								}
							});
						}
					});
					$(document).on('imageLoaded.FS', function (event) {
						paint();
					});
				});

			$(document).on('planetEnter.FS', function(event, planet){
				$hint.empty();
				$.each(planetRenderer.hintAttr, function (attr, title) {
					if (planet[attr] !== undefined && planet[attr] !== null) {
						$hint.append('<p>' + title + ': <strong>' + planet[attr] + '</strong></p>');
					}
				});
				$hint.show();
			});
			$(document).on('systemEnter.FS', function(event, system){
				$hint.empty();
				$.each(systemRenderer.hintAttr, function (attr, title) {
					if (system[attr] !== undefined && system[attr] !== null) {
						$hint.append('<p>' + title + ': <strong>' + system[attr] + '</strong></p>');
					}
				});
				$hint.show();
			});
			$(document).on('planetLeave.FS systemLeave.FS', function(event, system, planet){
				$hint.hide();
			});
			$(document).on('planetMove.FS systemMove.FS', function(event, system, planet){
				var mousePos = stage.getMousePosition();
				$hint.css({
					left: mousePos.x + 15,
					top: mousePos.y
				});
			});

			$(document).on('systemClick.FS', function (event, system) {
				//console.log(system);
				farSpace.fCall('f.system', 'show', [system.idSystem], {});
				//$(document).trigger("show.systemDialog.FS", system, null );
			});

			$(document).on('planetClick.FS', function (event, system, planet) {
				//console.log(system, planet);
				farSpace.fCall('f.system', 'show', [system.idSystem, planet.idPlanet], {});
				//$(document).trigger("show.systemDialog.FS", [system, planet] );
			});
		});


	</script>
	<?=JS::getFooterScripts(); ?>
</body>
</html>