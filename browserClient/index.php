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
	<script src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/jquery.validate.js"></script>
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
			var scale = 50,
				$canvas = $('#game'),
				canvas = $canvas[0],
				scanners = [];

			// элемент #myelement обрабатывает события прокрутка колесика мышки
            $canvas.mousewheel(function (event, delta) {
                scale = scale + (delta * 3);
                scale = (scale < 1) ? 1 : scale;
				scale = (scale > 700) ? 700 : scale;
                paint();
            });

			paint();

			function paint(){
				if (canvas && canvas.getContext) {
					var context = canvas.getContext('2d');
					context.canvas.width  = window.innerWidth;
					context.canvas.height = window.innerHeight;
					// coordinates
					var currX = 30;	//self.currX
					var currY = 25;	//self.currY
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
                                var currRange = Math.round(scanner.scannerPwr / 10 * scale * scannerPainter.scanner1range + 2);
                                var range1 = Math.round(scanner.scannerPwr / 10 * scale * scannerPainter.scanner1range);
                                var range2 = Math.round(scanner.scannerPwr / 10 * scale * scannerPainter.scanner2range);
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
					//Bkg
					context.fillStyle = "#000";
					context.fillRect(0, 0, rect.width, rect.height);

					//# clipping (TODO better one)
					//clip = mapSurface.get_clip()
					//# scanners
					//# scanner ranges and control areas
					//if self.showScanners or self.toggleControlAreas:
						//if self.toggleControlAreas:
							//self.drawControlAreas()
						//else:
							//self.drawScanners()
					//# pirate area
					//if self.showPirateAreas:
						//pass # TODO
					//# grid
                    scannerPainter.draw(scanners);
                    if (grid.showGrid) {
						grid.drawGrid();
					}

					// Рисуем окружность
					/*context.strokeStyle = "#000";
					context.beginPath();
					context.arc(100, 100, 50, 0, Math.PI * 2, true);
					context.closePath();
					context.stroke();
					context.fill();
					// Рисуем левый глаз
					context.fillStyle = "#fff";
					context.beginPath();
					context.arc(84, 90, 8, 0, Math.PI * 2, true);
					context.closePath();
					context.stroke();
					context.fill();
					// Рисуем правый глаз
					context.beginPath();
					context.arc(116, 90, 8, 0, Math.PI * 2, true);
					context.closePath();
					context.stroke();
					context.fill();
					// Рисуем рот
					context.beginPath();
					context.moveTo(70, 115);
					context.quadraticCurveTo(100, 130, 130, 115);
					context.quadraticCurveTo(100, 150, 70, 115);
					context.closePath();
					context.stroke();
					context.fill();*/
				}
			}

            $.ajax({
                url: "/ajax.php",
                type: 'POST',
                data: {action: 'scannerGetStaticMap'}
            }).done(function (data) {
				console.log(data);
				scanners = data.data.scanners;
				paint();
			});
		});


	</script>
</body>
</html>