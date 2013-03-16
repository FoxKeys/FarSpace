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
}

/*jslint browser: true, devel:true */
/*global $, browser:true, devel:true */
function FarSpace() {
    "use strict";
    /*global f */
    var self = this,
        host = document.location.protocol + '//' + document.location.host,
        ajaxURL = host + '/ajax.php',
        tokens = [];

    this.FSConst = new FarSpaceConst();

    this.ajax = function (classMethod, parameters, success, withToken) {
        if (withToken) {
            var token = tokens.pop();
            if (token) {
                parameters.unshift(token);
                return self.doAJAX(classMethod, parameters, success);
            }
            return self.doAJAX('auth.getToken', []).pipe(
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
    };
}

//Global FarSpace object
window.farSpace = new FarSpace();