/**
 * Created with JetBrains PhpStorm.
 * User: ad
 * Date: 17.03.13
 * Time: 22:26
 * To change this template use File | Settings | File Templates.
 */
/*jslint browser: true, devel:true */
/*global $, browser:true, devel:true */
function TImageCache() {
    "use strict";
    var self = this,
        cache = {};

    function loaded() {
        $(document).trigger("imageLoaded.FS");
    }

    self.get = function (fileName) {
        if (!cache[fileName]) {
            var imageObj = new Image();
            imageObj.onload = loaded;
            imageObj.src = fileName;
            cache[fileName] = imageObj;
        }
        return cache[fileName];
    };
}