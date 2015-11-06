(function ($) {
	$(function () {
		if ($('#frmCreateBooking').length > 0) {
			$('#frmCreateBooking').validate();
		}
		if ($('#frmUpdateBooking').length > 0) {
			$('#frmUpdateBooking').validate();
		}
		if ($('#frmCreateBooking').length > 0 || $('#frmUpdateBooking').length > 0) {
			$(".datepick").datepicker({
				dateFormat: "yy-mm-dd"
			});
		}
		
		var dates = {
		    convert: function (d) {
		        return (
		            d.constructor === Date ? d :
		            d.constructor === Array ? new Date(d[0], d[1], d[2]) :
		            d.constructor === Number ? new Date(d) :
		            d.constructor === String ? new Date(d) :
		            typeof d === "object" ? new Date(d.year, d.month, d.date) :
		            NaN
		        );
		    },
		    compare: function (a, b) {
		        return (
		            isFinite(a=this.convert(a).valueOf()) &&
		            isFinite(b=this.convert(b).valueOf()) ?
		            (a > b) - (a < b) :
		            NaN
		        );
		    },
		    inRange: function (d, start, end) {
		        return (
		            isFinite(d=this.convert(d).valueOf()) &&
		            isFinite(start=this.convert(start).valueOf()) &&
		            isFinite(end=this.convert(end).valueOf()) ?
		            start <= d && d <= end :
		            NaN
		        );
		    }
		};
		
		if ($(".timepicker").length > 0) {
			var opts = {
				dateFormat: "yy-mm-dd",
				timeFormat: "hh:mm",
				stepHour: 1,
				stepMinute: 5,
				onClose: function (dateText, inst) {
					var c, s, sd, st, start, end, e, ed, et, i, c, len;
					if (/from/.test(inst.id)) {
						// compare From and To dates
						var $end = $("#" + inst.id.replace(/from/, 'to')),
							endDate = $end.val();
							
						s = dateText.split(" ");
						sd = s[0].split("-");
						st = s[1].split(":");
						start = sd.concat(st, ["00"]);
						len = start.length;
						
						if (endDate.length > 0) {
							e = endDate.split(" ");
							ed = e[0].split("-");
							et = e[1].split(":");
							end = ed.concat(et, ["00"]);
												
							for (i = 0; i < len; i++) {
								start[i] = parseInt(start[i], 10);
								end[i] = parseInt(end[i], 10);
								if (i === 1) {
									// fix month
									start[i] -= 1;
									end[i] -= 1;
								}
							}
							c = dates.compare(new Date(start[0], start[1], start[2], start[3], start[4], start[5]), new Date(end[0], end[1], end[2], end[3], end[4], end[5]));
							if (c === 1) {
								// start > end
								$end.val(inst.input.val());
							}
						}
					} else if (/to/.test(inst.id)) {
						// compare Start and End dates
						var $start = $("#" + inst.id.replace(/to/, 'from')),
							startDate = $start.val();
							
						e = dateText.split(" ");
						ed = e[0].split("-");
						et = e[1].split(":");
						end = ed.concat(et, ["00"]);
						len = end.length;
						
						if (startDate.length > 0) {
							s = startDate.split(" ");
							sd = s[0].split("-");
							st = s[1].split(":");
							start = sd.concat(st, ["00"]);
						
							for (i = 0; i < len; i++) {
								start[i] = parseInt(start[i], 10);
								end[i] = parseInt(end[i], 10);
								if (i === 1) {
									// fix month
									start[i] -= 1;
									end[i] -= 1;
								}
							}
							c = dates.compare(new Date(start[0], start[1], start[2], start[3], start[4], start[5]), new Date(end[0], end[1], end[2], end[3], end[4], end[5]));
							if (c === 1) {
								// start > end
								$start.val(dateText);
							}
						}
					}
				}
			};
			$.datepicker._defaults.onSelect = function (dateText, inst) {
				var id = $(this).attr("id"), $start, $end, endDate;
				if (/from/.test(id)) {
					$start = this;
					$end = $("#" + id.replace(/from/, 'to'));
					endDate = $end.val();

					if (endDate.length === 0) {
						$end.datepicker("option", "minDate", dateText);
					}
										
				} else if (/to/.test(id)) {
					$end = this;
					$start = $("#" + id.replace(/to/, 'from'));
				}
			};
			$(".timepicker").datetimepicker(opts);
		}
		
		$("a.icon-delete").live("click", function (e) {
			e.preventDefault();
			$('#dialogDelete').data("id", $(this).attr('rel')).dialog('open');
		});
		
		if ($("#dialogDelete").length > 0) {
			$("#dialogDelete").dialog({
				autoOpen: false,
				resizable: false,
				draggable: false,
				height:220,
				modal: true,
				buttons: {
					'Delete': function() {
						$.ajax({
							type: "POST",
							data: {
								id: $(this).data("id")
							},
							url: "index.php?controller=AdminBookings&action=delete",
							success: function (res) {
								$("#content").html(res);
							}
						});
						$(this).dialog('close');			
					},
					'Cancel': function() {
						$(this).dialog('close');
					}
				}
			});
		}
		
		function getAvailability(dateText) {
			$.get("index.php?controller=AdminBookings&action=getAvailability", {
				date: dateText
			}).success(function (data) {
				$("#content").html(data);
			});
		}
		if ($("#date").length > 0) {
			getAvailability($("#date").val());
		}		
		
		$("#content").delegate("#type_id", "change", function () {
			$.get("index.php?controller=AdminBookings&action=getCars", {type_id: $("option:selected", this).val()}, function (data) {
				$("#boxCars").html(data);
			});
			$.get("index.php?controller=AdminBookings&action=getExtras", {type_id: $("option:selected", this).val()}, function (data) {
				$("#boxExtras").html(data);
			});
		}).delegate("#date", "focusin", function () {
			$(this).datepicker({
				dateFormat: $(this).attr('rev'),
				onSelect: function (dateText, inst) {
					getAvailability(dateText);
				}
			});
		}).delegate("#p_date", "focusin", function () {
			$(this).datepicker({
				dateFormat: $(this).attr('rev'),
				onSelect: function (dateText, inst) {
					window.location.href = 'index.php?controller=AdminBookings&action=index&p_date=' + dateText;
				}
			});
		}).delegate("#payment_method", "change", function () {
			if ($("option:selected", this).val() == 'creditcard') {
				$(".boxCC").show();
			} else {
				$(".boxCC").hide();
			}
		});
	});
})(jQuery);