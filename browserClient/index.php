<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * Author: Fox foxkeys@gmail.com
	 * Date Time: 10.02.2013 7:33
	 */
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>FarSpace 0.1</title>
    <meta name="description" content="The FarSpace game">
    <meta name="author" content="Sycoder">
	<link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/redmond/jquery-ui-1.10.0.custom.css">
    <!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

	<script src="js/jquery-1.9.0.js"></script>
	<script src="js/jquery-ui.js"></script>
	<script src="js/jquery.validate.js"></script>
	<script src="js/jquery.form.js"></script>
	<script src="js/jquery.mousewheel-3.1.1.js"></script>
	<script src="js/kinetic-v4.3.3.js"></script>
	<script src="js/f.js"></script>
	<script src="js/class.grid.js"></script>
	<script src="js/class.scanner.js"></script>
	<script src="js/class.planet.js"></script>
	<!--<script src="js/main.js"></script>-->
	<style>

		#container {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
		}
	</style>
</head>
<body>
	<?php //require_once( 'dialogs/login.php' );?>
	<div id="container">
	</div>
	<script>
		jQuery(function($){
			//# StarMapWidget overlays
			const	OID_NONE = 0;
			const	//## relations
					REL_ENEMY_LO = 0,
					REL_ENEMY = 0,
					REL_ENEMY_HI = 125,
					REL_UNFRIENDLY_LO = 125,
					REL_UNFRIENDLY = 250,
					REL_UNFRIENDLY_HI = 375,
					REL_NEUTRAL_LO = 375,
					REL_NEUTRAL = 500,
					REL_NEUTRAL_HI = 625,
					REL_FRIENDLY_LO = 625,
					REL_FRIENDLY = 750,
					REL_FRIENDLY_HI = 875,
					REL_ALLY_LO = 875,
					REL_ALLY = 1000,
					REL_ALLY_HI = 1000,
					REL_UNITY = 1250,
					REL_UNDEF = 100000,
					REL_DEFAULT = REL_NEUTRAL;
			const	T_PLAYER = 3,
					T_PIRPLAYER = 113,
					//## strategic resources
					SR_NONE = 0;
			const	MIN_SCALE = 10,
					MAX_SCALE = 600;
            var		scale = 50,
                    $container = $('#container'),
                    container = $container[0],
                    scanners = [],
                    systems = [],
					fleets = [],
                    starImages = {},
                    player = {type: T_PLAYER};

			var stage = new Kinetic.Stage({
				container: 'container',
				width: $container.width(),
				height: $container.height()
			});
			$(stage.getContent()).css('position', 'absolute');

			var shapesLayer = new Kinetic.Layer({clearBeforeDraw: false});
			var context = shapesLayer.getContext();
			var grid = new TGrid();
			var scanner = new TScanner();
			var planet = new TPlanet();
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
            });

            function cInt(value) {
                return parseInt(value, 10);
            }

            var res = {
                fadeColor: function (hex) {
                    var r = Math.round((parseInt(hex.substr(1, 2), 16) + 0xc0) / 2),
                            g = Math.round((parseInt(hex.substr(3, 2), 16) + 0xc0) / 2),
                            b = Math.round((parseInt(hex.substr(4, 2), 16) + 0xc0) / 2);
                    return '#' + r.toString(16) + g.toString(16) + b.toString(16);
                },
                getPlayerColor: function (owner, onlyDiplo) {
                    if (owner == OID_NONE) {
                        return res.getFFColorCode(REL_UNDEF);
                    }
                    if (!onlyDiplo) {
                        if (gdata.config.defaults.highlights == 'yes') {
                            if (gdata.playersHighlightColors.has_key(owner)) {
                                return gdata.playersHighlightColors[owner];
                            }
                        }
                    }
                    var rel = min(REL_UNDEF, client.getRelationTo(owner));
                    return getFFColorCode(rel)
                },
                getFFColorCode: function (relationship) {
                    if (relationship < 0) {
                        return '#ff00ff';
                    } else {
                        if (relationship < REL_UNFRIENDLY_LO) {
                            return '#ff8080';
                        } else {
                            if (relationship < REL_NEUTRAL_LO) {
                                return '#ff9001';
                            } else {
                                if (relationship < REL_FRIENDLY_LO) {
                                    return '#ffff00';
                                } else {
                                    if (relationship < REL_ALLY_LO) {
                                        return '#b0b0ff';
                                    } else {
                                        if (relationship <= REL_ALLY_HI) {
                                            return '#80ffff';
                                        } else {
                                            if (relationship == 1250) {
                                                return '#00ff00';
                                            } else {
                                                return '#C0C0C0';
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            };

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
                        overlayColorColumns: {},
						starToPointScaleThreshold: 30
					};

                    var systemsPainter = {
						nameFontHeight: 11,
						noOwnerBkgColor: 'c0c0c0',
						borderStrokeStyle: '#333366',
						starToCircleRadius: 7,
						starWidth: 22,
						starHeight: 22,
                        draw: function () {
							//# coordinates
                            var nameColor = res.getPlayerColor(OID_NONE, false);
							var color, starRadius;
							context.font = systemsPainter.nameFontHeight + "px Arial";
                            $.each(systems, function (index, system) {//for objID, x, y, name, img, color, namecolor, singlet, icons in self._map[self.MAP_SYSTEMS]:
                                var sx = cInt((system.x - currX) * scale) + centerX;
                                var sy = maxY - (cInt((system.y - currY) * scale) + centerY);

								planet.draw(system, sx, sy, overlayMode, shapesLayer, rect, currX, currY, scale );

								var textY = sy + Math.round( systemsPainter.starHeight / 2 );
                                var colors = {}, total = 0, delta = null;
                                if (system.planets) {
                                    $.each(system.planets, function (index, planet) {
                                        var colorIndex;
                                        if (planet.idPlayer && planet[self.overlayColorColumns[overlayMode]]) {
                                            total++;
                                            colorIndex = planet[self.overlayColorColumns[overlayMode]];
                                            colors[colorIndex] = (colors[colorIndex]) ? colors[colorIndex] + 1 : 1;
                                        }
                                    });
                                }
                                if (total == 0) {
                                    total = 1;
                                    colors[systemsPainter.noOwnerBkgColor] = 1;
                                }

                                if (scale >= self.starToPointScaleThreshold) {    //30
                                    var w, h, x, y;

                                    if (system.image) {
                                        w = system.image.width;
                                        h = system.image.height;
                                        x = sx - w / 2;
                                        y = sy - h / 2;
                                        context.drawImage(system.image, x, y);
                                    }
                                    //# images are now smaller - TODO fix images of stars
                                    w = 22;
                                    h = 22;
                                    if (system.name) {
                                        if (overlayMode != farSpace.FSConst.OVERLAY_OWNER) {
                                            context.fillStyle = res.fadeColor(nameColor);
                                        } else {
                                            context.fillStyle = nameColor;
                                        }
                                        context.fillText(system.name, sx - context.measureText(system.name).width / 2, textY);
                                    }
                                    //buoy = self.getBuoy(objID)
                                    if (system.buoy) {//ToDo how to?
										/*if not name: #if name not set and there is a bouy, set "?" as the name
											if overlayMode != gdata.OVERLAY_OWNER:
												namecolor = res.fadeColor(namecolor)
											img = renderText('small', '[ ? ]', 1, namecolor)
											self._mapSurf.blit(img, (sx - img.get_width() / 2, sy + h / 2))
											nSy = sy + h / 2 + img.get_height()
										nSy = sy + h / 2 + img.get_height()
										lines = buoy[0].split("\n")
										maxW = 0
										hh = 0
										for line in lines:
											if len(line) == 0:
												break
											if len(line) > MAX_BOUY_DISPLAY_LEN:
												line = u"%s..." % line[:MAX_BOUY_DISPLAY_LEN]
											if overlayMode == gdata.OVERLAY_OWNER:
												bouycolor = buoyColors[buoy[1] - 1]
											else:
												bouycolor = res.fadeColor(buoyColors[buoy[1] - 1])
											img = renderText('small', line, 1, bouycolor)
											maxW = max(img.get_width(), maxW)
											self._mapSurf.blit(img, (sx - img.get_width() / 2, nSy + hh))
											hh += img.get_height()
										if maxW > 0:
											actRect = Rect(sx - maxW / 2, nSy, maxW, hh)
											actRect.move_ip(self.rect.left, self.rect.top)
											self._actBuoyAreas[objID] = actRect

									}*/
									/*
                                    $.each(system.icons, function (index, icon) {
                                        context.drawImage(icon, x, y);
                                        x += icon.width + 1;
                                    });*/
									//# active rectangle
									//ToDo - how to?
									/*var actRect = Rect(sx - w / 2, sy - h / 2, w, h)
									actRect.move_ip(self.rect.left, self.rect.top)
									self._actAreas[objID] = actRect*/
                                } else {
									starRadius = Math.round( systemsPainter.starToCircleRadius * scale / self.starToPointScaleThreshold );
                                    if ((overlayMode == farSpace.FSConst.OVERLAY_OWNER || overlayMode == farSpace.FSConst.OVERLAY_DIPLO) && system.planets) {
                                        var angle = -Math.PI / 4;

										delta = 2 * Math.PI / total;

                                        $.each(colors, function (color, count) {
                                            context.beginPath();
                                            context.moveTo(sx, sy); // center of the pie
                                            context.arc(sx, sy, starRadius, angle, angle + delta * count, false);
                                            context.lineTo(sx, sy); // line back to the center
                                            context.closePath();
                                            context.fillStyle = '#' + color;
                                            context.fill();
                                            context.beginPath();
                                            context.arc(sx, sy, starRadius, 0, 2 * Math.PI, false);
                                            context.strokeStyle = systemsPainter.borderStrokeStyle;
                                            context.stroke();
                                            angle += (delta * count);
                                        });
                                    } else {
                                        color = system[self.overlayColorColumns[overlayMode]];
                                        context.beginPath();
                                        context.arc(sx, sy, starRadius, 0, 2 * Math.PI, false);
                                        context.fillStyle = '#' + color;
                                        context.fill();
                                    }
                                    if (system.name && scale > 15) {
                                        // add linear gradient
                                        var grd = context.createLinearGradient(sx, textY, sx, textY + systemsPainter.nameFontHeight);
										var start = 0;
										delta = 1 / total;
                                        $.each(colors, function (color, count) {
                                            if (overlayMode != farSpace.FSConst.OVERLAY_OWNER) {
                                                color = res.fadeColor(color)
                                            }
                                            grd.addColorStop(start, '#' + color);
                                            grd.addColorStop(start + delta, '#' + color);
                                            start += (delta * count);
                                        });
                                        context.fillStyle = grd;
                                        context.fillText(system.name, sx - context.measureText(system.name).width / 2, textY);

										/*buoy = self.getBuoy(objID)
										if buoy != None:
											lines = buoy[0].split("\n")
											nSy = sy + 6 / 2 + img.get_height()
											maxW = 0
											hh = 0
											for line in lines:
												if len(line) == 0:
													break
												img = renderText('small', line, 1, buoyColors[buoy[1] - 1])
												maxW = max(img.get_width(), maxW)
												self._mapSurf.blit(img, (sx - img.get_width() / 2, nSy + hh))
												hh += img.get_height()
											if maxW > 0:
												actRect = Rect(sx - maxW / 2, nSy, maxW, hh)
												actRect.move_ip(self.rect.left, self.rect.top)
												self._actBuoyAreas[objID] = actRect*/
/*                                    }
									//# active rectangle
									//ToDo - how to?
									/*actRect = Rect(sx - 6 / 2, sy - 6 / 2, 6, 6)
									actRect.move_ip(self.rect.left, self.rect.top)
									self._actAreas[objID] = actRect*/
                                	}
								}
                            }
                        })
                    }
					}

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
                        systemsPainter.draw();
                    }
					//# planets
					/*if (self.showPlanets) {
						planet.draw(systems, shapesLayer);
					}*/
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
				$.each(systems, function(index, system){
					if (!starImages[system.starClass]) {
						var imageObj = new Image();
						imageObj.onload = paint;
						imageObj.src = 'img/systems/star_' + system.starClass + '.png';
						starImages[system.starClass] = imageObj;
					}
					system.image = starImages[system.starClass];
					system.icons = [];
				});
				paint();
			});
		});


	</script>
</body>
</html>