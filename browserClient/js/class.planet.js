/**
 * Created with JetBrains PhpStorm.
 * User: ad
 * Date: 17.03.13
 * Time: 0:21
 * To change this template use File | Settings | File Templates.
 */
/*jslint browser: true, devel:true */
/*global $, browser:true, devel:true, Kinetic, farSpace */
function TPlanet() {
    "use strict";
    var self = this,
        minPlanetSymbolSize = 2,
        maxPlanetSymbolSize = 22;

    self.draw = function (system, sx, sy, overlayMode, shapesLayer, rect, currX, currY, scale) {
        if (scale > 20) {
            if (system.planets) {
                //# coordinates
                var rectSize = Math.max(minPlanetSymbolSize, Math.floor(scale / 6)),
                    rectSpace;
                if (maxPlanetSymbolSize !== 0) {
                    rectSize = Math.min(maxPlanetSymbolSize, rectSize);
                }
                rectSpace = rectSize + Math.max(1, Math.round(rectSize / 5));
                $.each(system.planets, function (index, planet) { //for objID, x, y, orbit, color, singlet in self._map[self.MAP_PLANETS]:
                    var rect = new Kinetic.Rect({
                        x: sx + (index % 8) * rectSpace + 13,
                        y: sy - rectSize,
                        width: rectSize,
                        height: rectSize,
                        fill: '#' + planet[farSpace.FSConst.overlayColorColumns[overlayMode]]
                    });
                    // add the shape to the layer
                    rect.on('click', function () {
                        console.log(this, planet);
                    });
                    shapesLayer.add(rect);
                });
            }
        }
    };
}