(function (window, undefined) {
	var document = window.document;
	
	function CR(options) {
		if (!(this instanceof CR)) {
			return new CR(options);
		}
		this.container = null;
		this.passed = {'first': false, 'second': false, 'third': false, 'fourth': false};
		this.current = 'loadSearch';
		this.opts = {
			folder: ""
		};
		for (var attr in options) {
			if (options.hasOwnProperty(attr)) {
				this.opts[attr] = options[attr];
			}
		}
		this.init();
		return this;
	}
	function dateFormat(str, format) {
		var jQuery = ['d', 'dd', 'm', 'mm', 'yy'],
			dateJs = ['d', 'dd', 'M', 'MM', 'yyyy'],
			php = ['j', 'd', 'n', 'm', 'Y'],
			limiters = ['.', '-', '/'],
			stack = [];
		switch (format) {
			case 'jquery':
				stack = jQuery;
				break;
			case 'datejs':
				stack = dateJs;
				break;
			default:
				return str;
		}
		for (var i = 0, len = limiters.length; i < len; i++) {
			if (str.indexOf(limiters[i]) !== -1) {
				var iFormat = str.split(limiters[i]);
				return [ 
					stack[php.indexOf(iFormat[0])], 
					stack[php.indexOf(iFormat[1])], 
					stack[php.indexOf(iFormat[2])]
				].join(limiters[i]);
			}
		}
		return str;
	}
	if (!Array.prototype.indexOf) {  
		Array.prototype.indexOf = function (searchElement /*, fromIndex */ ) {  
			"use strict";  
            if (this == null) {  
                throw new TypeError();  
            }  
            var t = Object(this);  
            var len = t.length >>> 0;  
            if (len === 0) {  
                return -1;  
            }  
            var n = 0;  
            if (arguments.length > 0) {  
                n = Number(arguments[1]);  
                if (n != n) { // shortcut for verifying if it's NaN  
                    n = 0;  
                } else if (n != 0 && n != Infinity && n != -Infinity) {  
                    n = (n > 0 || -1) * Math.floor(Math.abs(n));  
                }  
            }  
            if (n >= len) {  
                return -1;  
            }  
            var k = n >= 0 ? n : Math.max(len - Math.abs(n), 0);  
            for (; k < len; k++) {  
                if (k in t && t[k] === searchElement) {  
                    return k;  
                }  
            }  
            return -1;  
        }  
    }
	CR.prototype = {
		init: function () {
			var self = this;
			self.container = document.getElementById("crContainer");
			self.loadSearch();
			
			var btns = {};
			btns[self.opts.closeButton] = function (button) {
				this.close();
			};
			
			self.overlayTerms = new OverlayJS({
				selector: "crDialogTerms",
				modal: true,
				width: 640,
				height: 480,
				onBeforeOpen: function () {
					var that = this;
					JABB.Ajax.sendRequest(self.opts.folder + "index.php?controller=Front&action=getTerms", function (req) {
						that.content.innerHTML = req.responseText;
					});
				},
				buttons: btns
			});
			
			self.overlayMap = new OverlayJS({
				selector: "crDialogMap",
				width: 640,
				height: 480,
				modal: true,
				onOpen: function () {
					var that = this,
						canvasId = 'ojs-content-' + this.id;
						
					JABB.Ajax.getJSON(self.opts.folder + "index.php?controller=Front&action=getLocations", function (data) {
						var map = new google.maps.Map(document.getElementById(canvasId), {
							zoom: 8,
							mapTypeId: google.maps.MapTypeId.ROADMAP
						});
						
						if (data && data.length) {
							var i, len, markers = [], _latLng, _marker, _info;
							for (i = 0, len = data.length; i < len; i++) {
								_latLng = new google.maps.LatLng(data[i].lat, data[i].lng);
								_marker = new google.maps.Marker({
									map: map,
									position: _latLng
								});
								_info = new google.maps.InfoWindow({
									content: ['<span style="font-weight: bold; text-transform: uppercase">', data[i].name, '</span><br /><br />',
										data[i].state, ', ', data[i].city, ' ', data[i].zip, '<br />', 
										data[i].address_1, '<br /><br />', 
										'Email: <a href="mailto:', data[i].email, '">', data[i].email, '</a><br />',
										'Phone: ', data[i].phone, '<br /><br />',	
										'<span style="text-decoration: underline">Opening Time:</span><br />',
										data[i].opening_time.replace(/\n/, '<br />')].join("")
								});
								google.maps.event.addListener(_marker, "click", function (info, marker) {
									return function () {
										info.open(map, marker);
									};
								}(_info, _marker));
								if (i == len - 1) {
									map.setCenter(_latLng);
								}
								markers.push(_marker);
							}								
						}							
					});							
				},
				buttons: btns
			});
		},
		bindMenu: function () {
			var self = this,
				breadcrumbsEl = JABB.Utils.getElementsByClass("crBreadcrumbsEl", self.container, "A"),
				localeEl = JABB.Utils.getElementsByClass("crLocaleEl", self.container, "A"),
				i, len;
			for (i = 0, len = breadcrumbsEl.length; i < len; i++) {
				switch (parseInt(breadcrumbsEl[i].getAttribute("rel"), 10)) {
					case 2:
						if (!self.passed.first) {
							breadcrumbsEl[i].style.cursor = "default";
						}
						break;
					case 3:
						if (!self.passed.second) {
							breadcrumbsEl[i].style.cursor = "default";
						}
						break;
					case 4:
						if (!self.passed.third) {
							breadcrumbsEl[i].style.cursor = "default";
						}
						break;
				}
				breadcrumbsEl[i].onclick = function (inst) {
					return function (e) {
						switch (parseInt(this.getAttribute("rel"), 10)) {
							case 1:
								inst.loadSearch();
								break;
							case 2:
								if (inst.passed.first) {
									inst.loadCars();
								}
								break;
							case 3:
								if (inst.passed.second) {
									inst.loadExtras();
								}
								break;
							case 4:
								if (inst.passed.third) {
									inst.loadCheckout();
								}
								break;
						}
						if (e && e.preventDefault) {
							e.preventDefault();
						}
						return false;
					};
				}(self);
			}
			for (i = 0, len = localeEl.length; i < len; i++) {
				localeEl[i].onclick = function (inst) {
					return function (e) {
						inst.setLocale(this.getAttribute("rel"));
						if (e && e.preventDefault) {
							e.preventDefault();
						}
						return false;
					};
				}(self);
			}
		},
		bindTabs: function () {
			var self = this,
				arr = JABB.Utils.getElementsByClass("crTabsLink", self.container, "a"),
				i, len;
			for (i = 0, len = arr.length; i < len; i++) {
				arr[i].onclick = function (inst) {
					return function (e) {
						if (e && e.preventDefault) {
							e.preventDefault();
						}
						inst.loadCars.apply(inst, [null, this.getAttribute("rel"), inst.transmission]);
						return false;
					};
				}(self);
			}
			
			arr = JABB.Utils.getElementsByClass("crSort", self.container, "a");
			for (i = 0, len = arr.length; i < len; i++) {
				arr[i].onclick = function (inst) {
					return function (e) {
						if (e && e.preventDefault) {
							e.preventDefault();
						}
						var rel = this.getAttribute("rel");
						inst.loadCars.apply(inst, [null, inst.size, inst.transmission, rel.split("|")[0], rel.split("|")[1]]);
						return false;
					};
				}(self);
			}
			
			var transmission = document.getElementById("crTransmission");
			if (transmission) {
				transmission.onchange = function (e) {
					self.loadCars.apply(self, [null, self.size, this.options[this.selectedIndex].value, self.col_name, self.direction]);
				};
			}
		},
		
		getWorkingTime: function (frm) {
			var self = this;
			JABB.Ajax.sendRequest(self.opts.folder + "index.php?controller=Front&action=getWorkingTime", function (data) {
				document.getElementById("timeBox").innerHTML = data.responseText;
				
				cr_hour_from = document.getElementById("cr_hour_from");
				if (cr_hour_from) {
					cr_hour_from.onchange = function () {
						self.getHourTo.apply(self, [this.form]);
					};
					
				}
			}, JABB.Utils.serialize(frm));
		},
		
		getHourTo: function (frm) {
			var self = this;
			JABB.Ajax.sendRequest(self.opts.folder + "index.php?controller=Front&action=getHourTo", function (data) {
				document.getElementById("cr_hour_to").value = data.responseText;
			}, JABB.Utils.serialize(frm));
		},
		
		bindSearch: function () {
			var self = this,
				dateFrom = new Calendar({
					element: "cr_date_from",
					//dateFormat: "Y-m-d",
					dateFormat: self.opts.dateFormat,
					monthNamesFull: self.opts.monthNamesFull,
					dayNames: self.opts.dayNames,
					disablePast: true,
					onSelect: function () {
						self.getWorkingTime.apply(self, [document.getElementById("crFormSearch")]);
					}
				}),
				dateTo = new Calendar({
					element: "cr_date_to",
					//dateFormat: "Y-m-d",
					dateFormat: self.opts.dateFormat,
					monthNamesFull: self.opts.monthNamesFull,
					dayNames: self.opts.dayNames,
					disablePast: true,
					onSelect: function () {
						reCalc.call(self);
					}
				}),
				lnkFrom = document.getElementById("crDateFrom"),
				lnkTo = document.getElementById("crDateTo"),
				btnQuote = document.getElementById("crBtnQuote"),
				btnMap = document.getElementById("crBtnMap"),
				sameLoc = document.getElementById("cr_same_location"),
				returnLoc = document.getElementById("crReturnBox");
				
				duration = document.getElementById("duration");
				timeBox = document.getElementById("timeBox");
				cr_hour_from = document.getElementById("cr_hour_from");
				
			self.elFrom = document.getElementById("cr_date_from");
			self.elTo = document.getElementById("cr_date_to");
			self.elHFrom = document.getElementById("cr_hour_from");
			self.elMFrom = document.getElementById("cr_minutes_from");
			self.elHTo = document.getElementById("cr_hour_to");
			self.elMTo = document.getElementById("cr_minutes_to");
			
			function reCalc() {
				var from = Date.parseExact(this.elFrom.value, dateFormat(self.opts.dateFormat, 'datejs')).getTime() + (parseInt(this.elHFrom.value, 10) * 3600000) + (parseInt(this.elMFrom.value, 10) * 60000),
					to = Date.parseExact(this.elTo.value, dateFormat(self.opts.dateFormat, 'datejs')).getTime() + (parseInt(this.elHTo.value, 10) * 3600000) + (parseInt(this.elMTo.value, 10) * 60000),
					nd = document.getElementById("crNumDays"),
					days;
				if (from !== null && to !== null) {
					days = Math.ceil((to - from) / 86400000);
					if (days > 0) {
						nd.lastChild.innerHTML = days;
						nd.style.display = "";
					} else {
						nd.style.display = "none";
					}
				} else {
					nd.style.display = "none";
				}
			}	
			
			if (duration) {
				function bindDuration(el) {
					if (this.value == '1_1' || this.value == '1_E') {
						el.style.display = "";
					} else {
						el.style.display = "";
					}
					var that = this;
					if(that.value){
						self.getWorkingTime.apply(self, [that.form]);
						self.getHourTo.apply(self, [that.form]);
					}				
				}
				duration.onchange = function () {
					bindDuration.call(this, timeBox);
				};
				
			}
			
			
			
			if (lnkFrom) {
				lnkFrom.onclick = function (e) {
					if (e && e.preventDefault) {
						e.preventDefault();
					}
					dateFrom.isOpen ? dateFrom.close() : dateFrom.open();
					return false;
				};
			}
			if (lnkTo) {
				lnkTo.onclick = function (e) {
					if (e && e.preventDefault) {
						e.preventDefault();
					}
					dateTo.isOpen ? dateTo.close() : dateTo.open();
					return false;
				};
			}
			if (btnMap) {
				btnMap.onclick = function (e) {
					self.overlayMap.open();
					if (e && e.preventDefault) {
						e.preventDefault();
					}
					return false;
				}
			}
			if (sameLoc && returnLoc) {
				function bindLoc(el) {
					if (this.checked) {
						el.style.display = "none";
					} else {
						el.style.display = "";
					}
				}
				sameLoc.onchange = function () {
					bindLoc.call(this, returnLoc);
				};
				sameLoc.onclick = function () {
					bindLoc.call(this, returnLoc);
				};
			}
			if (btnQuote) {
				btnQuote.onclick = function () {
					this.disabled = true;
				/*	if (!self.validateSearch(this)) {
						this.disabled = false;
						return;
					}*/
					self.passed.first = true;
					self.loadCars.apply(self, [JABB.Utils.serialize(document.getElementById("crFormSearch"))]);
				};
			}
		},
		bindCars: function () {
			var self = this,
				btnContinue = JABB.Utils.getElementsByClass("crBtnContinue", self.container, "button"),
				i, len = btnContinue.length;
			for (i = 0; i < len; i++) {
				btnContinue[i].onclick = function (inst) {
					return function (e) {
						inst.passed.second = true;
						inst.type_id = this.value;
						inst.loadExtras();
					};
				}(self);
			}
		},
		bindExtras: function () {
			var self = this,
				btnCheckout = document.getElementById("crBtnCheckout"),
				btnConditions = document.getElementById("crBtnConditions"),
				btnWhen = document.getElementById("crBtnWhen"),
				btnChoise = document.getElementById("crBtnChoise"),
				add = JABB.Utils.getElementsByClass("crBtnAdd", self.container, "button"),
				remove = JABB.Utils.getElementsByClass("crBtnRemove", self.container, "button"),
				i, len;
			if (btnCheckout) {
				btnCheckout.onclick = function () {
					self.passed.third = true;
					self.loadCheckout();
				};
			}
			if (btnConditions) {
				btnConditions.onclick = function (e) {
					if (e && e.preventDefault) {
						e.preventDefault();
					}
					self.overlayTerms.open();
					return false;
				}
			}
			if (btnWhen) {
				btnWhen.onclick = function (e) {
					if (e && e.preventDefault) {
						e.preventDefault();
					}
					self.loadSearch();
					return false;
				};
			}
			if (btnChoise) {
				btnChoise.onclick = function (e) {
					if (e && e.preventDefault) {
						e.preventDefault();
					}
					self.loadCars();
					return false;
				};
			}
			for (i = 0, len = add.length; i < len; i++) {
				add[i].onclick = function (e) {
					self.addExtra.apply(self, [this.value]);
				};
			}
			for (i = 0, len = remove.length; i < len; i++) {
				remove[i].onclick = function (e) {
					self.removeExtra.apply(self, [this.value]);
				};
			}
		},
		bindCheckout: function () {
			var self = this,
				btnTerms = document.getElementById("crBtnTerms"),
				btnConfirm = document.getElementById("crBtnConfirm"),
				btnBack = document.getElementById("crBtnBack");				
			
			if (btnTerms) {
				btnTerms.onclick = function (e) {
					self.overlayTerms.open();
					if (e && e.preventDefault) {
						e.preventDefault();
					}
					return false;
				};
			}
			if (btnBack) {
				btnBack.onclick = function () {
					self.loadExtras();
				};
			}
			if (btnConfirm) {
				var frm = btnConfirm.form;
				if (frm) {
					var pm = frm.payment_method;
					if (pm) {
						pm.onchange = function (e) {
							var data = document.getElementById("crCCData"),
								names = ["cc_type", "cc_num", "cc_exp_month", "cc_exp_year", "cc_code"],
								i, len = names.length;
							switch (this.options[this.selectedIndex].value) {
								case 'creditcard':
									data.style.display = "";
									for (i = 0; i < len; i++) {
										JABB.Utils.addClass(frm[names[i]], "crRequired");
									}
									break;
								default:
									data.style.display = "none";
									for (i = 0; i < len; i++) {
										JABB.Utils.removeClass(frm[names[i]], "crRequired");
									}
							}
						};
					}
				}				
				
				btnConfirm.onclick = function () {
					var that = this;
					that.disabled = true;
					btnBack.disabled = true;
					if (!self.validateCheckoutForm(that)) {
						that.disabled = false;
						btnBack.disabled = false;
						return;
					}
					JABB.Ajax.postJSON(self.opts.folder + "index.php?controller=Front&action=bookingSave", function (data) {
						switch (data.code) {
							case 100:
								self.errorHandler('\n' + self.opts.message_4);
								that.disabled = false;
								btnBack.disabled = false;
								break;
							case 200:
								switch (data.payment) {
									case 'paypal':
										self.triggerLoading('message_1', self.container);
										self.loadPaymentForm(data);
										break;
									case 'authorize':
										self.triggerLoading('message_2', self.container);
										self.loadPaymentForm(data);
										break;
									case 'creditcard':
										self.triggerLoading('message_3', self.container);
										break;
									default:
										self.triggerLoading('message_3', self.container);
								}					 
								break;
						}
					}, JABB.Utils.serialize(that.form));
				};
			}
			
		},
		loadSearch: function () {
			var self = this;
			JABB.Ajax.sendRequest(self.opts.folder + "index.php?controller=Front&action=loadSearch", function (req) {
				self.container.innerHTML = req.responseText;
				self.bindSearch();
				self.bindMenu();
				self.getWorkingTime.apply(self, [document.getElementById("crFormSearch")]);
			});
			self.current = "loadSearch";
		},
		loadCars: function () {
			var self = this,
				post = typeof arguments[0] != "undefined" ? arguments[0] : null,
				qs = "";
			if (typeof arguments[1] != "undefined") {
				self.size = arguments[1];
			} else {
				self.size = "all";
			}
			qs += "&size=" + self.size;
			if (typeof arguments[2] != "undefined") {
				self.transmission = arguments[2];
			} else {
				self.transmission = "";
			}
			qs += "&transmission=" + self.transmission;
			if (typeof arguments[3] != "undefined") {
				self.col_name = arguments[3];
			} else {
				self.col_name = "t1.name";
			}
			qs += "&col_name=" + self.col_name;
			if (typeof arguments[4] != "undefined") {
				self.direction = arguments[4];
			} else {
				self.direction = "asc";
			}
			qs += "&direction=" + self.direction;
			JABB.Ajax.sendRequest(self.opts.folder + "index.php?controller=Front&action=loadCars" + qs, function (req) {
				self.container.innerHTML = req.responseText;
				self.bindCars();
				self.bindMenu();
				self.bindTabs();
			}, post);
			self.current = "loadCars";
		},
		loadExtras: function () {
			var self = this,
				qs = "&type_id=" + self.type_id;
			JABB.Ajax.sendRequest([self.opts.folder, "index.php?controller=Front&action=loadExtras", qs].join(""), function (req) {
				self.container.innerHTML = req.responseText;
				self.bindExtras();
				self.bindMenu();
//                                setupquiz();
//                                if(self.explevel)
//                                {
//                                    $("input[name='exp_level']").each(function(){
//                                        if($(this).val() == self.explevel)
//                                        {
//                                            $(this).attr("checked", "checked");
//                                        }
//                                    });
//                                }
			});
			self.current = "loadExtras";
		},
		loadCheckout: function () {
                        var self = this;
                        
//                        if(!self.explevel || self.explevel != $("input[name='exp_level']:checked").val())
//                        {
//                            self.explevel = $("input[name='exp_level']:checked").val();
//                        }
//                        quizdata = {
//                            q1: $("input[name='q1']:checked").val(),
//                            q2: $("input[name='q2']:checked").val(),
//                            q3: $("input[name='q3']:checked").val(),
//                            q4: $("input[name='q4']:checked").val(),
//                            q5: $("input[name='q5']:checked").val()   
//                        }
//                        count = 0;
//                        for(q in quizdata)
//                        {
//                            if(quizdata[q])
//                            {
//                                count++;
//                            } 
//                        }
                        
//                        if(count == 5)
//                        {
//                            $.ajax({
//                                type: "GET",
//                                url: self.opts.folder + "index.php?controller=Front&action=checkExperience",
//                                data: quizdata,
//                                success: function(response)
//                                {
                                   JABB.Ajax.sendRequest(self.opts.folder + "index.php?controller=Front&action=loadCheckout", function (req) {
                                            self.container.innerHTML = req.responseText;
                                            self.bindCheckout();
                                            self.bindMenu();
                                   });
                                    self.current = "loadCheckout";
//                                }
//                            });
//                        }
//                        else
//                        {
//                            alert("Please respond to experience questions");
//                            return;
//                        }
 
		},
		loadPaymentForm: function (obj) {
			var self = this,
				div;
			JABB.Ajax.sendRequest(self.opts.folder + "index.php?controller=Front&action=loadPayment", function (req) {
				div = document.createElement("div");
				div.innerHTML = req.responseText;
				self.container.appendChild(div);
				
				if (typeof document.forms[obj.payment == 'paypal' ? 'crPaypal' : 'crAuthorize'] != 'undefined') {
					document.forms[obj.payment == 'paypal' ? 'crPaypal' : 'crAuthorize'].submit();						
				}
			}, "payment_mod=" + obj.payment_mod + "&id=" + obj.booking_id);
		},
		addExtra: function (extra_id) {
			var self = this;
			JABB.Ajax.getJSON([self.opts.folder, "index.php?controller=Front&action=addExtra&extra_id=", extra_id].join(""), function (data) {
				self.loadExtras();
			});
			return self;
		},
		removeExtra: function (extra_id) {
			var self = this;
			JABB.Ajax.getJSON([self.opts.folder, "index.php?controller=Front&action=removeExtra&extra_id=", extra_id].join(""), function (data) {
				self.loadExtras();
			});
			return self;
		},
		setLocale: function (index) {
			var self = this;
			JABB.Ajax.getJSON([self.opts.folder, "index.php?controller=Front&action=setLocale&index=", index].join(""), function (data) {
				switch (self.current) {
					case 'loadSearch':
						self.loadSearch();
						break;
					case 'loadCars':
						self.loadCars();
						break;
					case 'loadExtras':
						self.loadExtras();
						break;
					case 'loadCheckout':
						self.loadCheckout();
						break;
				}
				if (window.myCR) {
					window.myCR.opts = data;
				}
			});
		},
		validateSearch: function (btn) {
			var frm = btn.form,
				df = frm.date_from,
				dt = frm.date_to;
			if (df && dt) {
				if (Date.parseExact(df.value, dateFormat(this.opts.dateFormat, 'datejs')).getTime() < Date.parseExact(dt.value, dateFormat(this.opts.dateFormat, 'datejs')).getTime()) {
					return true;
				}
			}
			this.errorHandler("\n - " + this.opts.validation.error_dates);
			return false;
		},
		validateCheckoutForm: function (btn) {
			var re = /([0-9a-zA-Z\.\-\_]+)@([0-9a-zA-Z\.\-\_]+)\.([0-9a-zA-Z\.\-\_]+)/,
				message = "";

			var frm = btn.form;
			for (var i = 0, len = frm.elements.length; i < len; i++) {
				var cls = frm.elements[i].className;
				if (cls.indexOf("crRequired") !== -1 && frm.elements[i].disabled === false) {
					switch (frm.elements[i].nodeName) {
					case "INPUT":
						switch (frm.elements[i].type) {
						case "checkbox":
						case "radio":
							if (!frm.elements[i].checked && frm.elements[i].getAttribute("rev")) {
								message += "\n - " + frm.elements[i].getAttribute("rev"); 
							}
							break;
						default:
							if (frm.elements[i].value.length === 0 && frm.elements[i].getAttribute("rev")) {
								message += "\n - " + frm.elements[i].getAttribute("rev");
							}
							break;
						}
						break;
					case "TEXTAREA":
						if (frm.elements[i].value.length === 0 && frm.elements[i].getAttribute("rev")) {						
							message += "\n - " + frm.elements[i].getAttribute("rev");
						}
						break;
					case "SELECT":
						switch (frm.elements[i].type) {
						case 'select-one':
							if (frm.elements[i].value.length === 0 && frm.elements[i].getAttribute("rev")) {
								message += "\n - " + frm.elements[i].getAttribute("rev"); 
							}
							break;
						case 'select-multiple':
							var has = false;
							for (j = frm.elements[i].options.length - 1; j >= 0; j = j - 1) {
								if (frm.elements[i].options[j].selected) {
									has = true;
									break;
								}
							}
							if (!has && frm.elements[i].getAttribute("rev")) {
								message += "\n - " + frm.elements[i].getAttribute("rev");
							}
							break;
						}
						break;
					default:
						break;
					}
				}
				if (cls.indexOf("crEmail") !== -1) {
					if (frm.elements[i].nodeName === "INPUT" && frm.elements[i].value.length > 0 && frm.elements[i].value.match(re) == null) {
						message += "\n - " + this.opts.validation.error_email;
					}
				}
			}
			if (message.length === 0) {
				return true;
			} else {
				this.errorHandler(message);		
				return false;
			}
		},
		errorHandler: function (message) {
			var err = JABB.Utils.getElementsByClass("crError", self.container, "P");
			if (err[0]) {
				err[0].innerHTML = '<span></span>' + this.opts.validation.error_title + message.replace(/\n/g, "<br />");
				err[0].style.display = '';
			} else {
				alert(this.opts.validation.error_title + message);
			}
		},
		triggerLoading: function (message, container) {
			if (container && container.nodeType) {
				container.innerHTML = this.opts[message];
			} else if (typeof container != "undefined") {
				var c = document.getElementById(container);
				if (c && c.nodeType) {
					c.innerHTML = this.opts[message];
				}
			}
		}
	};
	return (window.CR = CR);
})(window);

function setupquiz()
{
    currentquestion = 1;
    $(".next-btn").click(function(){
        
        if(checkforresponse(currentquestion))
        {
            $("#question-" + currentquestion).fadeOut("slow");
            currentquestion ++;
            $("#question-" + currentquestion).fadeIn("slow");
        }
        else
        {
            alert("Please answer all experience questions.");
        }
    });
}
function checkforresponse(whichquestion)
{
    radio_name = "q"+whichquestion;
    question_div = "#question-"+whichquestion;
    thisvalue = $('input[name='+radio_name+']:checked', question_div).val();
    if(thisvalue)
    {
        return true;
    }
    else
    {
        return false;
    }
}