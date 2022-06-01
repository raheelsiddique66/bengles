<div id="footer" class="bottom_round_corners">
	<div id="footer_content">
    	<address>&copy; <?php echo date("Y");?> - <?php echo $site_title; ?> Admin Panel</address>
    </div>
</div>
<script type="text/javascript" src="js/jquery.js"></script>
 <script src="js/jquery-ui.js"></script>
   <script>
  	$(document).ready(function(){
    $("a.click-option-main").click(function(){
        $(".click-option").slideToggle();
    });
});
</script>
<script type="text/javascript" src="js/fancybox/jquery.fancybox.min.js"></script>
<link href="js/fancybox/jquery.fancybox.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/moment/moment.min.js"></script>
<script type="text/javascript" src="js/full-calendar/fullcalendar.js"></script>
<link href="js/chosen/chosen.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/chosen/chosen.jquery.js"></script>
<script type="text/javascript" src="js/js.js"></script>
<script type="text/javascript" src="js/date-range-picker/daterangepicker.js"></script>
<script src="js/bootstrap-datetimepicker.min.js"></script>
<script>
$(document).ready(function(){
	if($(".select_multiple").length>0) $(".select_multiple").chosen();
	$('.date-picker').daterangepicker({ singleDatePicker: true, format: 'DD/MM/YYYY'});
	$('.fancybox_iframe').fancybox({type: 'iframe', width: '90%'});
	$('.fancybox-btn a.fancybox_inline').fancybox({type: 'inline'});
	$('.fancybox-btn a.f-iframe').fancybox({type: 'iframe', width: '90%', autoHeight: 1, afterClose: function(){ window.location.reload(); }});
	if(window.location != window.parent.location){
		$("body").addClass("popup-content");
	}
	$('.date-timepicker').datetimepicker({
		"format": 'DD/MM/YYYY hh:mm A'
	});
	$(".angular-datetimepicker").on("dp.change", function(){
		dp = $(this);
		angular.element($("#"+dp.data( 'controllerid' ))).scope().updateDate();
	});
});
</script>

<script type="text/javascript" src="js/angular.min.js"></script>
<script type="text/javascript" src="js/angular-animate.js"></script>
<script src="js/angular-moment.min.js"></script>
<script type="text/javascript" src="js/ui-bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/angularjs-datetime-picker.css" />
<script type="text/javascript" src="js/datetimepicker.js"></script>
<script type="text/javascript" src="js/angular-chosen.js"></script>
<script type="text/javascript" src="js/incoming.angular.js"></script>
<script type="text/javascript" src="js/washing.angular.js"></script>
<script type="text/javascript" src="js/delivery.angular.js"></script>
<script type="text/javascript" src="js/salary.angular.js"></script>
<script type="text/javascript" src="js/customer_dashboard.angular.js"></script>
<script type="text/javascript" src="js/new-delivery.angular.js"></script>
<script type="text/javascript" src="js/new-incoming.angular.js"></script>
<script type="text/javascript" src="js/vendor_dashboard.angular.js"></script>
<script type="text/javascript" src="js/vendor_outgoing.angular.js"></script>
<script type="text/javascript" src="js/new-vendor-outgoing.angular.js"></script>
<script type="text/javascript" src="js/vendor_delivery.angular.js"></script>
<script type="text/javascript" src="js/new-vendor-delivery.angular.js"></script>
<script type="text/javascript" src="js/new-washing.angular.js"></script>
<script type="text/javascript" src="js/production.angular.js"></script>
<script type="text/javascript" src="js/new-production.angular.js"></script>
<script src="js/jquery.UrduEditor.js" type="text/javascript"></script> 
<script>
$(document).ready(function() {
	$('#name_in_urdu_text').UrduEditor("14px"); 
	$(".sorting").hover(function(){
	  $icon=$(this).find(".sort-icon i");
	  $icon.removeClass("fa-angle-"+$icon.data("hover_out")).addClass("fa-angle-"+$icon.data("hover_in"))
	},function(){
	  $icon=$(this).find(".sort-icon i");
	  $icon.addClass("fa-angle-"+$icon.data("hover_out")).removeClass("fa-angle-"+$icon.data("hover_in"))
	});
	$(".reset_search").click(function(){
		$form = $(this).parents("form");
		$form.find('input[type=text], select, textarea').val('');
		$form.submit();
	});
}); 
function init_date_picker(){
	$('.date-picker').daterangepicker({ singleDatePicker: true, format: 'DD/MM/YYYY'});
}
</script>
</body>
</html>