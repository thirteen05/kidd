/*!
 * jQuery I18n plugin
 * 
 * Copyright 2012, Dimitar Ivanov (dimitar@stivasoft.com)
 *
 * Date: Wed Feb 22 14:22:35 2012 +0200
 */
(function ($, undefined) {
	var PROP_NAME = 'i18n',
		FALSE = false,
		TRUE = true;
	function I18n () {
		this._state = [];
		this._defaults = {
			numberOfLocales: 3,
			onSelect: null,
			onOpen: null,
			onClose: null
		};
	}
	$.extend(I18n.prototype, {
		_attachI18n: function (target, settings) {
			if (this._getInst(target)) {
				return FALSE;
			}
			var $target = $(target),
				self = this,
				inst = self._newInst($target),
				i18Holder, i18Switch, i18Menu, i18Li;
				
			$.extend(inst.settings, self._defaults, settings);
			self._state[inst.uid] = FALSE;
			
			var offset = $target.offset();
			i18Holder = $("<div>", {
				"id": "i18nHolder_" + inst.uid,
				"class": "i18n_holder",
				"css" : {
					"top": Math.ceil(offset.top) + "px",
					"left": Math.ceil(offset.left) + "px"
				}
			});
			i18Switch = $("<a>", {
				"id": "i18nSwitch_" + inst.uid,
				"class": "i18n_switch",
				"title": target.getAttribute("title"),
				"href": "#"
			}).bind("click", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				self._openI18n(target);
				return false;
			}).bind("mouseover", function () {
				//custom tooltip show
			}).bind("mouseout", function () {
				//custom tooltip hide
			});
			i18Menu = $("<ul>", {
				"id": "i18nMenu_" + inst.uid,
				"class": "i18n_menu"
			});
			for (var i = 1; i <= inst.settings.numberOfLocales; i++) {
				i18Li = $("<li>");
				$("<a>", {
					"class": "i18n_" + i,
					"rel": i,
					"href": "#"
				}).appendTo(i18Li);
				i18Li.appendTo(i18Menu);
			}			
			i18Menu.delegate("a", "click", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				self._selectI18n(target, $(this).attr("rel"));
				self._closeI18n(target);
				return false;
			})
			
			i18Switch.appendTo(i18Holder);
			i18Menu.appendTo(i18Holder);			
			i18Holder.insertAfter($target);
			
			inst.elSwitch = i18Switch;
			inst.elMenu = i18Menu;
			inst.elHolder = i18Holder;			
			$.data(target, PROP_NAME, inst);			
		},
		_isOpenI18n: function (target) {
			if (!target) {
				return FALSE;
			}
			var inst = this._getInst(target);
			return inst.isOpen;
		},
		_openI18n: function (target) {
			var inst = this._getInst(target);
			if (!inst) {
				return FALSE;
			}
			var onOpen = this._get(inst, 'onOpen');
			inst.elSwitch.hide().siblings(".i18n_menu").show();
			this._state[inst.uid] = TRUE;
			inst.isOpen = TRUE;
			if (onOpen) {
				onOpen.apply((inst.input ? inst.input[0] : null), [inst]);
			}
			$.data(target, PROP_NAME, inst);
		},
		_closeI18n: function (target) {
			var inst = this._getInst(target);
			if (!inst) {
				return FALSE;
			}
			var onClose = this._get(inst, 'onClose');
			inst.elMenu.hide().siblings(".i18n_switch").show();
			this._state[inst.uid] = FALSE;
			inst.isOpen = FALSE;
			if (onClose) {
				onClose.apply((inst.input ? inst.input[0] : null), [inst]);
			}
			$.data(target, PROP_NAME, inst);
		},
		_selectI18n: function (target, rel) {
			var inst = this._getInst(target);
			if (!inst) {
				return FALSE;
			}
			var onSelect = this._get(inst, 'onSelect');
			inst.elSwitch.removeClass("i18n_1 i18n_2 i18n_3").addClass("i18n_" + rel);
			if (onSelect) {
				onSelect.apply((inst.input ? inst.input[0] : null), [inst, rel]);
			}
			$.data(target, PROP_NAME, inst);
		},
		_newInst: function(target) {
			var id = target[0].id.replace(/([^A-Za-z0-9_-])/g, '\\\\$1');
			return {
				id: id, 
				input: target, 
				uid: Math.floor(Math.random() * 99999999),
				isOpen: FALSE,
				isDisabled: FALSE,
				settings: {}
			}; 
		},
		_getInst: function(target) {
			try {
				return $.data(target, PROP_NAME);
			}
			catch (err) {
				throw 'Missing instance data for this i18n';
			}
		},
		_get: function(inst, name) {
			return inst.settings[name] !== undefined ? inst.settings[name] : this._defaults[name];
		}
	});
	$.fn.i18n = function (options) {
		var otherArgs = Array.prototype.slice.call(arguments, 1);
		if (typeof options == 'string' && options == 'isDisabled') {
			return $.i18n['_' + options + 'I18n'].apply($.i18n, [this[0]].concat(otherArgs));
		}
		if (options == 'option' && arguments.length == 2 && typeof arguments[1] == 'string') {
			return $.i18n['_' + options + 'I18n'].apply($.i18n, [this[0]].concat(otherArgs));
		}
		return this.each(function() {
			typeof options == 'string' ?
				$.i18n['_' + options + 'I18n'].apply($.i18n, [this].concat(otherArgs)) :
				$.i18n._attachI18n(this, options);
		});
	};
	$.i18n = new I18n();
	$.i18n.version = "0.1";
})(jQuery);