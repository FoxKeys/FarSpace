/**
 * Created with JetBrains PhpStorm.
 * User: ad
 * Date: 17.03.13
 * Time: 1:32
 * To change this template use File | Settings | File Templates.
 */
/*jslint browser: true, devel:true */
/*global $, browser:true, devel:true, Kinetic, farSpace */
function TSystemRenderer() {
    "use strict";
    var self = this,
        defaultNameFontHeight = 11,
        noOwnerBkgColor = 'c0c0c0',
        starToCircleRadius = 4,
        starToPointScaleThreshold = 30;
    self.hintAttr = {idSystem: 'Id', level: 'Radar level', userName: 'Owner'};

    self.draw = function (systems, planet, overlayMode, context, shapesLayer, rect, currX, currY, scale, centerX, centerY) {
        //# coordinates
        var color,
            starRadius,
            nameFontHeight = Math.max(10, Math.round(defaultNameFontHeight * scale / farSpace.FSConst.defaultScale));

        $.each(systems, function (index, system) {//for objID, x, y, name, img, color, namecolor, singlet, icons in self._map[self.MAP_SYSTEMS]:
            var sx = farSpace.cInt((system.x - currX) * scale) + centerX,
                sy = rect.height - (farSpace.cInt((system.y - currY) * scale) + centerY),
                scaleCoeff = scale / farSpace.FSConst.defaultScale,
                w = farSpace.FSConst.starSize * scaleCoeff,
                h = farSpace.FSConst.starSize * scaleCoeff,
                textY = sy + Math.round(h / 3),
                colors = {},
                total = 0,
                delta = null,
                star,
                angle,
                text,
                grd,
                start,
                icon,
                iconPos = sx,
                iconW;

            planet.draw(system, sx, sy, overlayMode, shapesLayer, rect, currX, currY, scale);

            if (system.planets) {
                $.each(system.planets, function (index, planet) {
                    var colorIndex;
                    if (planet.idPlayer && planet[farSpace.FSConst.overlayColorColumns[overlayMode]]) {
                        total += 1;
                        colorIndex = planet[farSpace.FSConst.overlayColorColumns[overlayMode]];
                        colors[colorIndex] = (colors[colorIndex]) ? colors[colorIndex] + 1 : 1;
                    }
                });
            }
            if (total === 0) {
                total = 1;
                colors[noOwnerBkgColor] = 1;
            }

            if (scale >= starToPointScaleThreshold) {    //30
                if (system.image) {
                    star = new Kinetic.Image({
                        x: sx - Math.round(w / 2),
                        y: sy - Math.round(h / 2),
                        image: system.image,
                        width: w,
                        height: h
                    });
                }
                //buoy = getBuoy(objID)
                /*if (system.buoy) {
                     if not name: #if name not set and there is a bouy, set "?" as the name
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
                if (system.icons) {
                    $.each(system.icons, function (index, image) {
                        iconW = Math.round(image.width * scaleCoeff);
                        icon = new Kinetic.Image({
                            x: iconPos - iconW,
                            y: sy - Math.round(h / 2),
                            image: image,
                            width: iconW,
                            height: Math.round(image.height * scaleCoeff)
                        });
                        /*icon.on('click', function () {
                            $(document).trigger("iconClick.FS", index);
                        });
                        icon.on('mousemove', function () {
                            $(document).trigger("iconMove.FS", index);
                        });
                        icon.on('mouseenter', function () {
                            $(document).trigger("stratResEnter.FS", index);
                        });
                        icon.on('mouseleave', function () {
                            $(document).trigger("stratResLeave.FS", index);
                        });*/
                        shapesLayer.add(icon);
                        icon.moveToTop();
                        iconPos -= (iconW + 2);//2px gap
                    });
                }
            } else {
                starRadius = Math.round(starToCircleRadius * scale / starToPointScaleThreshold);
                if ((overlayMode === farSpace.FSConst.OVERLAY_OWNER || overlayMode === farSpace.FSConst.OVERLAY_DIPLO) && system.planets) {
                    star = new Kinetic.Shape({
                        drawFunc: function (canvas) {
                            angle = -Math.PI / 4;
                            delta = 2 * Math.PI / total;
                            var context = canvas.getContext();
                            $.each(colors, function (color, count) {
                                context.beginPath();
                                context.moveTo(sx, sy); // center of the pie
                                context.arc(sx, sy, starRadius, angle, angle + delta * count, false);
                                context.lineTo(sx, sy); // line back to the center
                                context.closePath();
                                context.fillStyle = '#' + color;
                                context.fill();
                                angle += (delta * count);
                            });
                        }
                    });
                } else {
                    color = system[farSpace.FSConst.overlayColorColumns[overlayMode]];
                    star = new Kinetic.Shape({
                        drawFunc: function (canvas) {
                            var context = canvas.getContext();
                            context.beginPath();
                            context.arc(sx, sy, starRadius, 0, 2 * Math.PI, false);
                            context.fillStyle = '#' + color;
                            context.fill();
                        }
                    });
                }
                /*if (system.name && scale > 15) {
                    buoy = self.getBuoy(objID)
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
                     self._actBuoyAreas[objID] = actRect
                }*/
            }
            //System name
            if (system.name && scale > 15) {
                // add linear gradient
                grd = context.createLinearGradient(0, 0, 0, 1);
                start = 0;
                delta = 1 / total;
                $.each(colors, function (color, count) {
                    if (overlayMode !== farSpace.FSConst.OVERLAY_OWNER) {
                        color = farSpace.fadeColor(color);
                    }
                    grd.addColorStop(start, '#' + color);
                    grd.addColorStop(start + delta, '#' + color);
                    start += (delta * count);
                });
                text = new Kinetic.Text({
                    x: sx,
                    y: textY,
                    text: system.name,
                    fontSize: nameFontHeight,
                    fontFamily: 'Arial',
                    fill: grd
                });
                text.setX(Math.round(sx - text.getWidth() / 2));
                shapesLayer.add(text);
            }

            //Setup events
            star.on('click', function () {
                $(document).trigger("systemClick.FS", system);
            });
            star.on('mousemove', function () {
                $(document).trigger("systemMove.FS", system);
            });
            star.on('mouseenter', function () {
                $(document).trigger("systemEnter.FS", system);
            });
            star.on('mouseleave', function () {
                $(document).trigger("systemLeave.FS", system);
            });
            shapesLayer.add(star);
            star.moveToBottom();
        });
    };
}