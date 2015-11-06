(function ($) {
	$(function () {
		$i18n_selector = $(".i18n_selector");
		if ($i18n_selector.length > 0) {
			$i18n_selector.i18n({
				numberOfLocales: 3,
				onSelect: function (inst, index) {
					var context = inst.elHolder.parent();
					$(":input[name^='i18n']", context).hide();
					$(":input[name^='i18n[" + index + "]']", context).show();
				}
			});
		}
		
		if ($(".textarea-install").length > 0) {
			$(".textarea-install").focus(function () {
				$(this).select();
			});
		}
		if ($(":input[name='value-enum-payment_enable_paypal']").length > 0) {
			$(":input[name='value-enum-payment_enable_paypal']").change(function () {
				var val = $(this).val(),
					$row = $(":input[name='value-string-paypal_address']").parent().parent();
				switch (val.split("::")[1]) {
					case "Yes":
						$row.show();
						break;
					case "No":
						$row.hide();
						break;	
				}
			});
		}
		if ($(":input[name='value-enum-payment_enable_authorize']").length > 0) {
			$(":input[name='value-enum-payment_enable_authorize']").change(function () {
				var val = $(this).val(),
					$row1 = $(":input[name='value-string-payment_authorize_key']").parent().parent(),
					$row2 = $(":input[name='value-string-payment_authorize_mid']").parent().parent();
				switch (val.split("::")[1]) {
					case "Yes":
						$row1.show();
						$row2.show();
						break;
					case "No":
						$row1.hide();
						$row2.hide();
						break;	
				}
			});
		}
		$("#content").delegate(".icon-delete", "click", function (e) {
			if (e.preventDefault) {
				e.preventDefault();
			}
			$(this).parent().parent().remove();
			return false;
		}).delegate("#btnAddPrice", "click", function (){
			$("#tblClone tr:first").clone().appendTo("#tblPrices tbody")
		}).delegate(".datepick", "focusin", function () {
			$(this).datepicker({
				dateFormat: $(this).attr('rev')
			});
		});
		
		$tblPrices = $("#tblPrices");
		if ($tblPrices.length > 0)
		{
			$("form").bind("submit", function (e) {
				if (e.preventDefault) {
					e.preventDefault();
				}
				
				var post, num,
					i = 0,
					$that = $(this),
					$tbody = $("#tblPrices tbody"),
					$tr = $("tr", $tbody),
					len = $tr.length,
					perLoop = 100,
					loops = len > perLoop ? Math.ceil(len / perLoop) : 1;
					
				num = loops;
				
				$that.find(":input").attr("readonly", "readonly");
				
				$(".bxStatus").hide();
				$(".bxStatusStart").show();
				$.post("index.php?controller=AdminOptions&action=deletePrices").done(function () {
					setPrices();
				});

				function setPrices() {
					$.ajaxSetup({async:false});
					post = $tr.slice(i * perLoop, (i + 1) * perLoop).find(":input").serialize();
					i++;
					$.post("index.php?controller=AdminOptions&action=setPrices", post, callback);
				}
				
				function callback() {
					num--;
					if (num > 0) {
				        setPrices();
				    } else {
				    	$that.find(":input").removeAttr("readonly");
				    	$(".bxStatusStart").hide();
				    	$(".bxStatusEnd").show().fadeOut(2500);
				        return;
				    }
				}
				
				return false;
			});
			
		}
	});
})(jQuery);