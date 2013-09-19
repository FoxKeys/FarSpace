/**
 * Created with JetBrains PhpStorm.
 * User: ad
 * Date: 25.09.12
 * Time: 10:27
 * To change this template use File | Settings | File Templates.
 */

function FarSpaceConst() {
    "use strict";
    this.OVERLAY_OWNER = "owner";
    this.OVERLAY_DIPLO = "diplomacy";
    this.OVERLAY_BIO = "bio";
    this.OVERLAY_FAME = "fame";
    this.OVERLAY_MIN = "min";
    this.OVERLAY_SLOT = "slot";
    this.OVERLAY_STARGATE = "stargate";
    this.OVERLAY_DOCK = "dock";
    this.OVERLAY_MORALE = "morale";
    this.OVERLAY_PIRATECOLONYCOST = "piratecolony";
    this.OVERLAY_TYPES = [this.OVERLAY_OWNER, this.OVERLAY_DIPLO, this.OVERLAY_BIO, this.OVERLAY_FAME, this.OVERLAY_MIN, this.OVERLAY_SLOT, this.OVERLAY_STARGATE, this.OVERLAY_DOCK, this.OVERLAY_MORALE, this.OVERLAY_PIRATECOLONYCOST];
    this.overlayColorColumns = {};
    this.overlayColorColumns[this.OVERLAY_OWNER] = 'overlayColorOwner';
    this.overlayColorColumns[this.OVERLAY_DIPLO] = 'overlayColorDiplomacy';
    this.defaultScale = 50;
    this.starSize = 40;
}

/*jslint browser: true, devel:true */
/*global $, browser:true, devel:true, fSettings:true */
function FarSpace() {
    "use strict";
    /*global f */
    var self = this,
        f = this,
        debug = console !== undefined,
        logId = 0,
        host = document.location.protocol + '//' + document.location.host,
        ajaxURL = host + '/ajax.php',
        blocksURL = host + '/getBlock.php',
        tokens = [],
        modules = {},
        modulesRegistry = {};

    this.FSConst = new FarSpaceConst();

    $.each(fSettings.loadedScripts, function (index, script) {
        modulesRegistry[index] = $.Deferred().resolve(true);
    });

    this.registerModule = function (name, module) {
        modules[name] = module;
    };

    this.log = function () {
        if (debug) {
            var args = Array.prototype.slice.call(arguments),
                date = new Date();
            args.unshift(date + '.' + (logId = logId + 1) + ' - ');  //logId - To prevent Firebug log records merging
            console.log.apply(console, args);
        }
    };

    this.cInt = function (value) {
        return parseInt(value, 10);
    };

    this.fadeColor = function (hex) {
        var r = Math.round((parseInt(hex.substr(1, 2), 16) + 0xc0) / 2),
            g = Math.round((parseInt(hex.substr(3, 2), 16) + 0xc0) / 2),
            b = Math.round((parseInt(hex.substr(4, 2), 16) + 0xc0) / 2);
        return '#' + r.toString(16) + g.toString(16) + b.toString(16);
    };

    this.ajax = function (classMethod, parameters, success, withToken) {
        if (withToken) {
            var token = tokens.pop();
            if (token) {
                parameters.unshift(token);
                return self.doAJAX(classMethod, parameters, success);
            }
            return self.doAJAX('auth.getToken', {}).pipe(
                function (token) {
                    parameters.unshift(token);
                    return self.doAJAX(classMethod, parameters, success);
                }
            );
        }
        return self.doAJAX(classMethod, parameters, success);
    };

    this.setupAjaxSubmit = function ($form, formOptions, validateOptions) {
        var validateSettings = {
            submitHandler: function () {
                self.doAJAX($form.attr('action'), $form.formSerialize(), function (result) {
                    console.log('result', result);
                });
                return false;   // return false to prevent normal browser submit and page navigation
            }
        };
        $.extend(validateSettings, validateOptions);
        $form.validate(validateSettings);
    };
/* @deprecated
    this.doAJAX = function (URL, data, success) {
        return $.ajax({
            type: 'post',
            url: URL,
            data: data
        }).pipe(
            function (data) {
                if (!data || data.data === 'undefined' || data.result === 'undefined' || data.errorCode === 'undefined' || data.errorMessage === 'undefined' || data.token === 'undefined') {    //Check packet format
                    return ($.Deferred().reject({errorCode: -1, errorMessage: 'Bad server answer: ' + data}));
                }
                if (data.token) {
                    tokens.push(data.token);
                }
                if (!data.result) {
                    return ($.Deferred().reject({errorCode: data.errorCode, errorMessage: data.errorMessage}));
                }
                return data.data;
            },
            function (response) {
                return {errorCode: -1, errorMessage: 'Unexpected error: ' + response.status + ' ' + response.statusText};
            }
        ).done(
            function (data) {
                if ($.isFunction(success)) {
                    success(data);
                }
            }
        ).fail(
            function (error) {
                if (console !== 'undefined') {
                    console.log('AJAX error: ', error);
                }
                alert('AJAX error: ' + error.errorMessage);
            }
        );
    };*/
    this.ajaxCheck = function (data) {
        var error = null;
        if (!data || data.data === undefined || data.result === undefined || data.errorCode === undefined || data.errorMessage === undefined || data.token === undefined) {    //Check packet format
            error = {errorCode: -1, errorMessage: 'Bad server answer: ' + data};
        }
        if (data.token) {
            tokens.push(data.token);
        }
        if (!data.result) {
            error = {errorCode: data.errorCode, errorMessage: data.errorMessage};
        }
        if (error) {
            $(document).trigger("fAjaxCheckError.f", error);
            return $.Deferred().reject(error);
        }
        return data.data;
    };

    this.ajaxError = function (response) {
        var error = {errorCode: -1, errorMessage: 'Ajax error: ' + response.status + ' ' + response.statusText};
        if (response.readyState === 4) {
            $(document).trigger("fAjaxError.f", error);
        }
        return error;
    };

    this.doAJAX = function (classMethod, parameters) {
        f.log('doAJAX', $.extend(parameters, {action: classMethod}));
        return $.ajax({
            type: 'post',
            url: ajaxURL,
            data: $.extend(parameters, {action: classMethod})
        }).then(
            self.ajaxCheck,
            self.ajaxError
        );
    };

    this.getToken = function () {
        var token = tokens.pop();
        if (token) {
            return $.Deferred().resolve(token);
        }
        return self.doAJAX('authGetToken', {});
    };

    this.addAjaxOverlay = function ($element) {
        if ($element.size() > 0) {
            var $overlay = $('<span class="ajax-overlay"><span class="img"></span></span>');

            $element.data('ajaxOverlay', $overlay);

            $overlay.css({
                left: $element[0].offsetLeft,
                top: $element[0].offsetTop,
                width: $element[0].offsetWidth,
                height: $element[0].offsetHeight
            });
            $($element[0].offsetParent).append($overlay);
        }
    };

    this.removeAjaxOverlay = function ($element) {
        if ($element.size() > 0) {
            $element.data('ajaxOverlay').remove();
        }
    };

    this.load = function (blockName, parameters, $element) {
        f.addAjaxOverlay($element);
        return f.getToken().then(
            function (token) {
                var $container;
                if ($element !== undefined && $element.size() > 0) {
                    $container = $element;
                } else {
                    $container = $('<div>');
                    $('body').append($container);
                }
                return $container.load(
                    blocksURL,
                    $.param($.extend({token: token, blockName: blockName}, parameters)),//Important, $.param because should be string for GET. GET needed to allow caching
                    function (response, status, xhr) {
                        f.removeAjaxOverlay($element);
                        if (status !== 'success') {
                            $(document).trigger("fAjaxError.f", {errorCode: -1, errorMessage: 'Ajax error: ' + response.status + ' ' + response.statusText});
                        }
                    }
                );
            }
        );
    };

    this.getScript = function (module, requestParams) {
        var dfd = $.Deferred(),
            deps = [];

        if (modulesRegistry[module] === undefined) {

            modulesRegistry[module] = dfd.promise();

            if (fSettings.scripts[module] === undefined) {
                dfd.reject({errorCode: -1, errorMessage: 'Module "' + module + '" not registered.'});
            } else {
                $.each(fSettings.scripts[module], function (index, script) {//Iterate dependencies
                    deps.push(f.getScript(script, requestParams));
                });

                $.when.apply($, deps)
                    .then(function () {
                        $.ajax(
                            blocksURL,
                            {
                                data: $.extend({blockName: 'JS', module: module}, requestParams),
                                cache: true,
                                type: "GET"
                            }
                        ).done(function (data, textStatus, jqXHR) {
                            try {
                                $('body').append($(data));
                                dfd.resolve();
                            } catch (e) {
                                dfd.reject({errorCode: -1, errorMessage: e.message});
                            }
                        }).fail(function (jqxhr, settings, exception) {
                            dfd.reject({errorCode: jqxhr.status, errorMessage: jqxhr.responseText});
                        });
                    })
                    .fail(function (error) {
                        dfd.reject(error);
                    });
            }
        }
        return modulesRegistry[module];
    };

    this.fCall = function (module, method, methodParams, requestParams) {
        var context = this,
            dfd = $.Deferred();

        function doCall(module, method, args) {
            if (modules[module] !== undefined) {
                if ($.isFunction(modules[module][method])) {
                    $.when(modules[module][method].apply(context, (args !== undefined) ? args : []))
                        .done(function (callResult) {
                            dfd.resolve(callResult);
                        })
                        .fail(function (callResult) {
                            dfd.reject(callResult);
                        });
                } else {
                    $(document).trigger("fCommonError.f", {errorCode: -1, errorMessage: 'fCall error: method modules.' + module + '.' + method + ' is undefined'});
                    dfd.reject({errorCode: -1, errorMessage: 'fCall error: method modules.' + module + '.' + method + ' is undefined'});
                }
            } else {
                $(document).trigger("fCommonError.f", [{errorCode: -1, errorMessage: 'fCall error: module modules.' + module + ' is undefined'}, module]);
                dfd.reject({errorCode: -1, errorMessage: 'fCall error: module modules.' + module + ' is undefined'});
            }
        }

        f.getScript(module, requestParams)
            .done(function () {
                doCall(module, method, methodParams);
            })
            .fail(function (error) {
                $(document).trigger("fGetScriptFail.f", [error, module]);
                dfd.reject(error);
            });

        return dfd.promise();
    };


    $(document).on("fAjaxCheckError.f fAjaxError.f fCommonError.f fGetScriptFail.f", function (event, error, module) {
        f.log(event.type, event, error);
        if (module === undefined || module !== 'f.messages') {
            f.fCall('f.messages', 'alert', [event.type + ': ' + error.errorMessage], {});
        } else {
            alert(event.type + ': ' + error.errorMessage);
        }
    });

}

//Global FarSpace object
window.farSpace = new FarSpace();