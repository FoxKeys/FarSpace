/**
 * Created with JetBrains PhpStorm.
 * User: ad
 * Date: 17.03.13
 * Time: 0:21
 * To change this template use File | Settings | File Templates.
 */
/*jslint browser: true, devel:true */
/*global $, browser:true, devel:true, Kinetic, farSpace */
function TPlanetRenderer() {
    "use strict";
    var self = this,
        minPlanetSymbolSize = 2,
        maxPlanetSymbolSize = 22;
    self.hintAttr = {idPlanet: 'Id', level: 'Radar level', namePlanetType: 'Type', name: 'Name', plBio: 'Environment', plMin: 'Minerals', plEn: 'Energy', plSlots: 'Slots', nameStratRes: 'Strat. resource', userName: 'Owner', hasRefuel: 'Has refuel', refuelInc: 'Refuel inc (%)', refuelMax: 'Refuel max (%)'};

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
                        x: sx + (index % 8) * rectSpace + Math.round(farSpace.FSConst.starSize * scale / farSpace.FSConst.defaultScale / 3),
                        y: sy - rectSize,
                        width: rectSize,
                        height: rectSize,
                        fill: '#' + planet[farSpace.FSConst.overlayColorColumns[overlayMode]]
                    });
                    //Setup events
                    rect.on('click', function () {
                        $(document).trigger("planetClick.FS", system, planet);
                    });
                    rect.on('mousemove', function () {
                        $(document).trigger("planetMove.FS", system, planet);
                    });
                    rect.on('mouseenter', function () {
                        $(document).trigger("planetEnter.FS", system, planet);
                    });
                    rect.on('mouseleave', function () {
                        $(document).trigger("planetLeave.FS", system, planet);
                    });
                    // add the shape to the layer
                    shapesLayer.add(rect);
                    rect.moveToTop();
                });
            }
        }
    };
}