/**
 * Created with JetBrains PhpStorm.
 * User: ad
 * Date: 16.03.13
 * Time: 23:49
 * To change this template use File | Settings | File Templates.
 */
function TGrid() {
    "use strict";
    var self = this,
        showGrid = true,
        showCoords = true,
        coordsFontHeight = 10,
        gridStrokeStyle1 = '#000090',
        gridStrokeStyle2 = '#333366',
        gridFontFillStyle = '#707080';

    self.draw = function (context, rect, currX, currY, scale, centerX, centerY) {
        if (showGrid) {
            var value = 0,
                left = Math.round((Math.round(currX) - currX) * scale) + centerX - Math.round(rect.width / scale / 2) * scale,
                top = Math.round((Math.round(currY) - currY) * scale) + centerY - Math.round(rect.height / scale / 2) * scale,
                x = 0.5 + left, //Half-pixel shift
                y = 0.5 + top,
                yScreen;
            context.lineWidth = 1;
            context.font = coordsFontHeight + "px Arial";
            context.fillStyle = gridFontFillStyle;
            context.textBaseline = 'top';
            while (x < left + rect.width + scale) {
                value = Math.floor((x - centerX) / scale + currX);

                context.strokeStyle = (value % 5 === 0) ? gridStrokeStyle1 : gridStrokeStyle2;
                context.beginPath();
                context.moveTo(x, rect.top);
                context.lineTo(x, rect.bottom);
                context.stroke();

                if (showCoords && value % 5 === 0) {
                    context.fillText(value.toString(), x + 2, rect.height - coordsFontHeight);
                }

                x += scale;
            }
            while (y < top + rect.height + scale) {
                yScreen = rect.height - y;
                value = Math.floor(((rect.height - yScreen) - centerY) / scale + currY);

                context.strokeStyle = (value % 5 === 0) ? gridStrokeStyle1 : gridStrokeStyle2;
                context.beginPath();
                context.moveTo(rect.left, yScreen);
                context.lineTo(rect.right, yScreen);
                context.stroke();

                if (showCoords && value % 5 === 0) {
                    context.fillText(value.toString(), 0, yScreen);
                }

                y += scale;
            }
        }
    };
}