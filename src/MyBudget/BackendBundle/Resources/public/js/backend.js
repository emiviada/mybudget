(function($, undefined) {
	"use strict";
	
	window.MB_APP = {
		
		global: {},
		/*
		 * The common functionalities this will be executed in all pages.
		 */
		common : {
			/*
			 * This is executed First of all.
			 */
			init : function() {
				var datepicker_field = $('.datepicker-field'),
                    myDate,
                    input_value,
                    formatted;

                //Set default date if we're editing
                input_value = datepicker_field.val();
                formatted = input_value.substring(6, 10) + '/' + input_value.substring(3, 5) + '/' + input_value.substring(0, 2);
                myDate = new Date(formatted);

                datepicker_field.datepicker({
                    showOn: "button",
                    buttonImage: "/bundles/backend/images/calendar-icon.jpg",
                    buttonImageOnly: true,
                    dateFormat: "dd/mm/yy",
                    defaultDate: myDate
                });
			},

			/*
			 * This is executed after all functionalities.
			 */
			finalize : function() {
				
			}
		},

		/*
		 * Content Module
		 */
		backend : {

			'init' : function() {
				//This code will be executed in all pages of this module
			},

			/*
			 * General functionalities for Backend
			 */
			'backend' : function() {
				
			}
		}
	};

	var UTIL = {
		exec : function(controller, action) {
			var ns = MB_APP,
                action = (action === undefined ) ? "init" : action;

			if (controller !== "" && ns[controller] && typeof ns[controller][action] == "function") {
				ns[controller][action]();
			}
		},

		init : function() {
			
			var body = document.body,
                controller = body.getAttribute("data-controller"),
                action = body.getAttribute("data-action");

			UTIL.exec("common");
			UTIL.exec(controller);
			UTIL.exec(controller, action);
			UTIL.exec("common", "finalize");
		}
	};

	$(document).ready(UTIL.init);

})(jQuery);