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
	<script src="js/f.js"></script>
	<script src="js/main.js"></script>
	<style>
        #game {
			position: absolute;
   			top:0;
			left:0;
            width: 100%;
            height: 100%;
        }
	</style>
</head>
<body>
	<?php //require_once( 'dialogs/login.php' );?>
	<canvas id="game"></canvas>
	<script>
		jQuery(function($){
			//# StarMapWidget overlays
			const	OVERLAY_OWNER = "owner",
					OVERLAY_DIPLO = "diplomacy",
					OVERLAY_BIO = "bio",
					OVERLAY_FAME = "fame",
					OVERLAY_MIN = "min",
					OVERLAY_SLOT = "slot",
					OVERLAY_STARGATE = "stargate",
					OVERLAY_DOCK = "dock",
					OVERLAY_MORALE = "morale",
					OVERLAY_PIRATECOLONYCOST = "piratecolony",
					OVERLAY_TYPES = [OVERLAY_OWNER, OVERLAY_DIPLO, OVERLAY_BIO, OVERLAY_FAME, OVERLAY_MIN, OVERLAY_SLOT, OVERLAY_STARGATE, OVERLAY_DOCK, OVERLAY_MORALE, OVERLAY_PIRATECOLONYCOST];
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
                    $canvas = $('#game'),
                    canvas = $canvas[0],
                    scanners = [],
                    systems = [],
                    starImages = {},
                    player = {type: T_PLAYER};

			// элемент #myelement обрабатывает события прокрутка колесика мышки
            $canvas.mousewheel(function (event, delta) {
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
				if (canvas && canvas.getContext) {
					var self = {
						showScanners: true,
						toggleControlAreas: false,
						showPirateAreas: false,
						showRedirects: false,
						showGateSystems: false,
						showGateNetworks: false,
						showSystems: true,
						overlayMode: OVERLAY_OWNER,
                        overlayColorColumns: {},
						starToPointScaleThreshold: 30
					};
					self.overlayColorColumns[OVERLAY_OWNER] = 'overlayColorOwner';
					self.overlayColorColumns[OVERLAY_DIPLO] = 'overlayColorDiplomacy';
					var context = canvas.getContext('2d');
					context.canvas.width  = window.innerWidth;
					context.canvas.height = window.innerHeight;
					context.textBaseline = 'top';
					// coordinates
					var currX = 15;
					var currY = -20;
					var rect = {top: 0, left:0, right: context.canvas.width, width: context.canvas.width, height: context.canvas.height, bottom: context.canvas.height};
					var maxX = rect.width;
					var maxY = rect.height;
					var centerX = Math.round(rect.width / 2);
					var centerY = Math.round(rect.height / 2);

					var grid = {
						showGrid: true,
						showCoords: true,
						coordsFontHeight: 10,
						gridStrokeStyle1: '#000090',
						gridStrokeStyle2: '#333366',
						gridFontFillStyle: '#707080',
						drawGrid: function () {
							var value = 0;
							var left = Math.round((Math.round(currX) - currX) * scale) + centerX - Math.round(rect.width / scale / 2) * scale;
							var x = 0.5 + left;	//Half-pixel shift
							context.lineWidth = 1;
							context.font = grid.coordsFontHeight + "px Arial";
							context.fillStyle = grid.gridFontFillStyle;
							while (x < left + rect.width + scale) {
								value = Math.floor((x - centerX) / scale + currX);

								context.strokeStyle = (value % 5 == 0) ? grid.gridStrokeStyle1 : grid.gridStrokeStyle2;
								context.beginPath();
								context.moveTo(x, rect.top);
								context.lineTo(x, rect.bottom);
								context.stroke();

                                if (grid.showCoords && value % 5 == 0) {
									context.fillText(value, x + 2, rect.height - grid.coordsFontHeight);
                                }

								x += scale;
							}
							var top = Math.round((Math.round(currY) - currY) * scale) + centerY - Math.round(rect.height / scale / 2) * scale;
							var y = 0.5 + top,	//Half-pixel shift
								yScreen;
							while (y < top + rect.height + scale) {
								yScreen = maxY - y;
								value = Math.floor(((maxY - yScreen) - centerY) / scale + currY);

								context.strokeStyle = (value % 5 == 0) ? grid.gridStrokeStyle1 : grid.gridStrokeStyle2;
								context.beginPath();
								context.moveTo(rect.left, yScreen);
								context.lineTo(rect.right, yScreen);
								context.stroke();

                                if (grid.showCoords && value % 5 == 0) {
									context.fillText(value, 0, yScreen);
                                }

								y += scale;
							}
						}
					};

                    var scannerPainter = {
                        //# default scanner ranges (inner and outer circles)
                        scanner1range: 1.0 / 10,
                        scanner2range: 1.0 / 16,
                        draw: function (scanners) {
                            //# coordinates
                            var scannerCalced = [];
                            //# draw
                            context.lineWidth = 1;
                            $.each(scanners, function (index, scanner) {
                                var sx = Math.round((scanner.x - currX) * scale) + centerX;
                                var sy = maxY - (Math.round((scanner.y - currY) * scale) + centerY);
                                var currRange = Math.round(scanner.scannerPwr * scale * scannerPainter.scanner1range + 2);
                                var range1 = Math.round(scanner.scannerPwr * scale * scannerPainter.scanner1range);
                                var range2 = Math.round(scanner.scannerPwr * scale * scannerPainter.scanner2range);
                                if (sx + currRange > 0 && sx - currRange < maxX && sy + currRange > 0 && sy - currRange < maxY) {
                                    context.beginPath();
                                    context.arc(sx, sy, currRange, 0, 2 * Math.PI, false);
                                    context.fillStyle = '#000060';
                                    context.fill();
                                    context.strokeStyle = '#000060';
                                    context.stroke();
                                    scannerCalced.push({sx: sx, sy: sy, range1: range1, range2: range2});
                                }
                            });
                            $.each(scannerCalced, function (index, info) {
                                context.beginPath();
                                context.arc(info.sx, info.sy, info.range1, 0, 2 * Math.PI, false);
                                context.fillStyle = '#000030';
                                context.fill();
                            });
                            $.each(scannerCalced, function (index, info) {
                                context.beginPath();
                                context.arc(info.sx, info.sy, info.range2, 0, 2 * Math.PI, false);
                                context.fillStyle = '#000040';
                                context.fill();
                            });
                        }
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
								var textY = sy + Math.round( systemsPainter.starHeight / 2 );
                                var colors = {}, total = 0, delta = null;
                                if (system.planets) {
                                    $.each(system.planets, function (index, planet) {
                                        var colorIndex;
                                        if (planet.idPlayer && planet[self.overlayColorColumns[self.overlayMode]]) {
                                            total++;
                                            colorIndex = planet[self.overlayColorColumns[self.overlayMode]];
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
                                        if (self.overlayMode != OVERLAY_OWNER) {
                                            context.fillStyle = res.fadeColor(nameColor);
                                        } else {
                                            context.fillStyle = nameColor;
                                        }
                                        context.fillText(system.name, sx - context.measureText(system.name).width / 2, textY);
                                    }
                                    //buoy = self.getBuoy(objID)
                                    if (system.buoy) {//ToDo how to?
										/*if not name: #if name not set and there is a bouy, set "?" as the name
											if self.overlayMode != gdata.OVERLAY_OWNER:
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
											if self.overlayMode == gdata.OVERLAY_OWNER:
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
										*/
									}
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
                                    if ((self.overlayMode == OVERLAY_OWNER || self.overlayMode == OVERLAY_DIPLO) && system.planets) {
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
                                        color = system[self.overlayColorColumns[self.overlayMode]];
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
                                            if (self.overlayMode != OVERLAY_OWNER) {
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
                                    }
									//# active rectangle
									//ToDo - how to?
									/*actRect = Rect(sx - 6 / 2, sy - 6 / 2, 6, 6)
									actRect.move_ip(self.rect.left, self.rect.top)
									self._actAreas[objID] = actRect*/
                                }
                            })
                        }
                    };
					//Bkg
					context.fillStyle = "#000";
					context.fillRect(0, 0, rect.width, rect.height);

					//# scanners
					//# scanner ranges and control areas
                    if (self.showScanners || self.toggleControlAreas) {
                        if (self.toggleControlAreas) {
                            console.log('ToDo: drawControlAreas');
                            //self.drawControlAreas();
                        }
                        else {
                            scannerPainter.draw(scanners);
                        }
                    }
                    //# pirate area
                    if (self.showPirateAreas) {
                        console.log('ToDo: showPirateAreas');
                    }
					//# grid
                    if (grid.showGrid) {
						grid.drawGrid();
					}
					//# redirections
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
					}
					//# stars
                    if (self.showSystems) {
                        systemsPainter.draw();
                    }
					/*
					# planets
					if self.showPlanets:
						self.drawPlanets()
					# fleets
					if self.showFleets:
						self.drawFleets()
					# clean up flag
					self.repaintHotbuttons = 1
					self.repaintMap = 0
					*/
				}
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