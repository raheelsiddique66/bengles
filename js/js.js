function getToday(){
	var mydate=new Date();
	var year=mydate.getYear();
	if (year < 1000)
	year+=1900;
	var day=mydate.getDay();
	var month=mydate.getMonth();
	var daym=mydate.getDate();
	if (daym<10)
	daym="0"+daym;
	var dayarray=new Array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
	var montharray=new Array("January","February","March","April","May","June","July","August","September","October","November","December");
	return(montharray[month]+" "+daym+", "+year);
}
function updateClock ( ){
  var currentTime = new Date ( );

  var currentHours = currentTime.getHours ( );
  var currentMinutes = currentTime.getMinutes ( );
  var currentSeconds = currentTime.getSeconds ( );

  // Pad the minutes and seconds with leading zeros, if required
  currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
  currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;

  // Choose either "AM" or "PM" as appropriate
  var timeOfDay = ( currentHours < 12 ) ? "AM" : "PM";

  // Convert the hours component to 12-hour format if needed
  currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;

  // Convert an hours component of "0" to "12"
  currentHours = ( currentHours == 0 ) ? 12 : currentHours;

  // Compose the string for display
  var currentTimeString = currentHours + ":" + currentMinutes ;

  // Update the time display
  document.getElementById("clock").innerHTML = "asdasdasd";
}
function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}
$(window).load(function(){
	$("#right_content").css("min-height", $("#left_content").css("height"));
	$(".file-select-option").change(function(){
		$target_container=jQuery("#"+$(this).attr('data-selected-file-display'));
		console.log($target_container);
		$src=jQuery(this).find('option:selected').attr('data-img-src');
		if($target_container.length && $src)
			$target_container.html('<img src="'+$src+'" />');
	});
	$(".file-select-option").change();
	$(".file-select-button").click(function(){
		$target_container=jQuery("#"+$(this).attr('data-selected-file-display'));
		$target_input=jQuery("#"+$(this).attr('data-input-field-id'));
		$target_input.unbind('change').change(function(){
			$target_container.html(jQuery(this).val());
		});
		$target_input.click();
	});
});
$(document).ready(function(){
	$(".file-select-option").change(function(){
		$target_container=jQuery("#"+$(this).attr('data-selected-file-display'));
		console.log($target_container);
		$src=jQuery(this).find('option:selected').attr('data-img-src');
		if($target_container.length && $src)
			$target_container.html('<img src="'+$src+'" />');
	});
	$(".file-select-option").change();
	$(".file-select-button").click(function(){
		$target_container=jQuery("#"+$(this).attr('data-selected-file-display'));
		$target_input=jQuery("#"+$(this).attr('data-input-field-id'));
		$target_input.unbind('change').change(function(){
			$target_container.html(jQuery(this).val());
		});
		$target_input.click();
	});
	if($(".select_multiple").length>0) $(".select_multiple").chosen();
	
	$("#select_all").change(function(){
		var chckedstatus=this.checked;
		$(".list :checkbox").each(function(){
			this.checked=chckedstatus;
		});
	});
	$("#apply_bulk_action").click(function(){
			$slectedvalue=$('#bulk_action option:selected').val();
			if($slectedvalue != "null"){
				$IDs='';
				$(".list :checkbox").each(function(){
					if(this.checked)
						if(this.value!=0)
							$IDs+=this.value+',';
				});
				$IDs=$IDs.substring(0,$IDs.length-1);
				var pagename= window.location.pathname;
				pagename=pagename.substring(pagename.lastIndexOf("/") + 1);
				window.location.href=pagename+'?tab=bulk_action&action='+$slectedvalue+'&Ids='+$IDs+'&pid='+getUrlVars()["pid"]+'&ppid='+getUrlVars()["ppid"];
			}
	});
	if($("#seo_url")){
		$("#seo_url").keyup(function(){
			$new=$(this).attr('value').toLowerCase();							  
			$(this).attr('value',$new.replace(" ", "-"));
		});
	}
	$(".dropdown.link").click(function(){
		$(".dropdown-menu.dropdown-menu-right").toggle();
	});
	$("#topstats.btn").click(function(){
		$(".topstats.search_filter").slideToggle();
	});
	$(".sidebar-open-button").click(function(){
		$(".sidebar").toggle();
		$(".sidebar").toggleClass("active");
		if($(window).width()>768){
			if($(".sidebar").hasClass('active')){
				$(".content").css("margin-left","250px");
			}
			else{
				$(".content").css("margin-left","0");
			}
		}
		/*$(".sidebar").toggle()
		if($(window).width()>768){
			if($(".sidebar").hasClass('closed')){
				$(".sidebar").removeClass('closed')
				$(".content").css("margin-left","250px");
			}
			else{
				$(".sidebar").addClass('closed')
				$(".content").css("margin-left","0px");
			}
		}*/
	});
	$('.nav > li > a').click(function(){
		if ($(this).attr('class') != 'active'){
		  	$('.nav li ul').slideUp();
		  	$(this).next().slideToggle();
		  	$('.nav li a').removeClass('active');
		  	$(this).addClass('active');
		}
		else{
			$(this).next().slideToggle();
		  	$(this).removeClass('active');
		}
	});
	$("select.select_supplier").chosen({width: "100%"});
	
	if($(".item_select").length>0){
		var $type = $(".item_select").first().data( "type" );
		$clone_container = $("."+$type);
		$(".item_select").change(function(){
			var $item_id=$(this).find('option:selected').val();
			var $item = $(this);
			var $container=$(this).parents("."+$item.data( "type" ));
			var $id = 0;
			if ($("input[name='id']").length > 0) {
				$id=$("input[name='id']").val();
			}
			switch( $item.data( "type" ) ) {
				case "purchase_item":
					$url = "purchase_manage.php";
					$data = {"tab": "get_unit_price", "id": $item_id, 'parent_id': $id}
					$field_class = ".purchase_price";
					$field_with_tax_class = ".purchase_price_with_tax";
				break;
				case "purchase_return_item":
					$url = "purchase_return_manage.php";
					$data = {"tab": "get_unit_price", "id": $item_id, 'parent_id': $id, 'purchase_id': $( "#purchase_id" ).val()}
					$field_class = ".purchase_price";
					$field_with_tax_class = ".purchase_price_with_tax";
				break;
				case "sales_item":
					$url = "sales_manage.php";
					$data = {"tab": "get_unit_price", "id": $item_id, 'parent_id': $id, 'customer_id': $( "#customer_id" ).val()}
					$field_class = ".sales_price";
					$field_with_tax_class = ".sales_price_with_tax";
				break;
				case "sales_return_item":
					$url = "sales_return_manage.php";
					$data = {"tab": "get_unit_price", "id": $item_id, 'parent_id': $id, 'customer_id': $( "#customer_id" ).val(), 'sales_id': $( "#sales_id" ).val()}
					$field_class = ".sales_price";
					$field_with_tax_class = ".sales_price_with_tax";
				break;
			}
			if($item_id>0){
				$.get($url, $data, function($prices){
					$prices = JSON.parse($prices)
					$container.find($field_class).val($prices[0]);
					$container.find($field_with_tax_class).val($prices[1]);
					update_total($container);
				});
			}
			else{
				update_total($container);
			}
		}).change();	
		$("."+$type+" input[type=text]").change(function(){
			$container=$(this).parents("."+$type);
			update_total($container);
		});
		$("#discount, #payment_account_id").change(function(){
			$this = $(this);
			update_total($("."+$type).first());
		});
		$(".add_list_item").click(function(e){
			e.preventDefault();
			$this = $(this);
			$container=$this.parents("."+$type).last();
			$new_container = $clone_container.clone(true);
			$new_container.find( '.item_select' ).val('');
			$new_container.find( 'input[type=text]' ).val(0);
			$new_container.insertAfter($container);
			$("."+$type).last().find("select.item_select").chosen({"width": "100%"});
			update_total($container);
		});
		$(".delete_list_item").click(function(e){
			e.preventDefault();
			$this = $(this);
			if($(".delete_list_item").length>1){
				$container = $this.parents("."+ $type );
				$temp_container = $container.prev() || $container.next();
				$container.remove();
				update_total($temp_container);
			}
			else{
				alert("There must an item in a list.");
			}
		});
		$clone_container = $clone_container.last().clone(true);
		$("select.item_select").chosen({width: "100%"});
		$( "#customer_id" ).change( function(){
			$(".item_select").change();
		} ).change();
	}
});
function update_total($container){
	var $type = $(".item_select").first().data( "type" );
	switch( $type ) {
		case "purchase_item":
			$field_class = ".purchase_price";
			$field_with_tax_class = ".purchase_price_with_tax";
		break;
		case "purchase_return_item":
			$field_class = ".purchase_price";
			$field_with_tax_class = ".purchase_price_with_tax";
		break;
		case "sales_item":
			$field_class = ".sales_price";
			$field_with_tax_class = ".sales_price_with_tax";
		break;
		case "sales_return_item":
			$field_class = ".sales_price";
			$field_with_tax_class = ".sales_price_with_tax";
		break;
	}
	$container.find(".total_price").val(((parseFloat($container.find($field_with_tax_class).val()))*parseFloat($container.find(".quantity").val())).toFixed(2));
	$container=$container.parent();
	$grand_total_item=$grand_total_price=0;
	$sn=1;
	$container.find("."+$type).each(function(){
		$(this).find(".serial_number").html($sn);
		$grand_total_item+=parseFloat($(this).find(".quantity").val());
		$grand_total_price+=parseFloat($(this).find(".total_price").val());
		$sn++;
	});
	$grand_total_price -= parseFloat($("#discount").val());
	$container.find('.grand_total_item').html($grand_total_item);
	if( $grand_total_price >= 0 ) {
		$container.find('.grand_total_price').html((Math.round($grand_total_price * 100) / 100));
	}
	else{
		$container.find('.grand_total_price').html("("+(Math.round(Math.abs($grand_total_price)* 100) / 100)+")");
	}
	if( $("input[name='id']").length == 0 && $('#payment_amount').length > 0 && $('#payment_account_id').val() != ''  ){
		$('#payment_amount').val(Math.round(Math.abs($grand_total_price)* 100) / 100);
	}
}