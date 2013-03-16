/**
 * Created with JetBrains PhpStorm.
 * User: ad
 * Date: 17.03.13
 * Time: 0:08
 * To change this template use File | Settings | File Templates.
 */
/*jslint browser: true, devel:true */
/*global $, browser:true, devel:true */
function TScanner() {
    "use strict";
    var self = this,
    //# default scanner ranges (inner and outer circles)
        scanner1range = 1.0 / 10,
        scanner2range = 1.0 / 16;
    self.draw = function (scanners, context, rect, currX, currY, scale, centerX, centerY) {
        //# coordinates
        var scannerCalced = [];
        //# draw
        context.lineWidth = 1;
        $.each(scanners, function (index, scanner) {
            var sx = Math.round((scanner.x - currX) * scale) + centerX,
                sy = rect.height - (Math.round((scanner.y - currY) * scale) + centerY),
                currRange = Math.round(scanner.scannerPwr * scale * scanner1range + 2),
                range1 = Math.round(scanner.scannerPwr * scale * scanner1range),
                range2 = Math.round(scanner.scannerPwr * scale * scanner2range);
            if (sx + currRange > 0 && sx - currRange < rect.width && sy + currRange > 0 && sy - currRange < rect.height) {
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
    };
}
