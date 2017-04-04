jQuery(document).ready(function($) {
	$('#Ad_nextSlide').on('click',function(){
		var currentSlide = $('[id^="slide-"]').filter('.activeSlide');
		var nextSlide = currentSlide.next();
		currentSlide.slideUp().removeClass('activeSlide');
		nextSlide.slideDown(500,function(){
			scrollToTop();
		}).addClass('activeSlide');
		if(nextSlide.is('#slide-3') && !nextSlide.is('.triggeredMap')){
			myMap();
			nextSlide.addClass('triggeredMap');
		}
		adjustBtns();
		var targetSlide = $('[id^="slide-"]').filter('.activeSlide');
		var targetNav = $('a[data-target="'+targetSlide.attr('id')+'"]');
		$('#slide-nav li').filter('.active').removeClass('active');
		targetNav.parent().addClass('active');
	});
	$('#Ad_prevSlide').on('click',function(){
		var currentSlide = $('[id^="slide-"]').filter('.activeSlide');
		var prevSlide = currentSlide.prev();
		currentSlide.slideUp().removeClass('activeSlide');
		prevSlide.slideDown(500,function(){
			scrollToTop();
		}).addClass('activeSlide');
		if(prevSlide.is('#slide-3') && !prevSlide.is('.triggeredMap')){
			myMap();
			prevSlide.addClass('triggeredMap');
		}
		adjustBtns();
		var targetSlide = $('[id^="slide-"]').filter('.activeSlide');
		var targetNav = $('a[data-target="'+targetSlide.attr('id')+'"]');
		$('#slide-nav li').filter('.active').removeClass('active');
		targetNav.parent().addClass('active');
	});
	function adjustBtns (){
		if($('#slide-6').is('.activeSlide')){
			$('#Ad_nextSlide').attr('disabled','disabled');
		}else{
			$('#Ad_nextSlide').removeAttr('disabled');
		}
		if($('#slide-1').is('.activeSlide')){
			$('#Ad_prevSlide').attr('disabled','disabled');
		}else{
			$('#Ad_prevSlide').removeAttr('disabled');
		}
	};

	$('#slide-nav').on('click','a', function(e){
		e.preventDefault();
		$(this).blur();
		if($(this).parent().hasClass('active')){
			return;
		}
		var targetSlide = $('[id="'+ $(this).attr('data-target') +'"]');
		var currentSlide = $('[id^="slide-"]').filter('.activeSlide');
		currentSlide.slideUp().removeClass('activeSlide');
		targetSlide.slideDown().addClass('activeSlide');
		if(targetSlide.is('#slide-3')&& !targetSlide.is('.triggeredMap')){
			myMap();
			targetSlide.addClass('triggeredMap');
		}
		adjustBtns();
		$('#slide-nav li').filter('.active').removeClass('active');
		$(this).parent().addClass('active');

	});

	function scrollToTop (){
		$('html, body').animate({
			scrollTop: 0
		}, 200);
	}

	$('#Ad_priority').on('rating.change', function(event, value, caption) {
		$('#Ad_priority').attr("value",value).val(value);
	});
	$('#Ad_priority').on('rating.clear', function(event, value, caption) {
		$('#Ad_priority').attr("value",'').val('');
	});

	$('#AdForm').validator();


	$(".date-group").datepicker({
		autoclose: true,
		format: 'yyyy-mm-dd',
		todayBtn: false,
		todayHighlight: true,
		orientation: 'left bottom',
		clearBtn: false,
		startDate: '0d',
		inputs: $('[data-date]'),
	});
	$('[data-date]').datepicker().on('show', function(e) {
		var correctedLeft = $(e.currentTarget).offset().left;
		var correctedTop = $(e.currentTarget).offset().top + 38;
		var correctedRight = $(window).width() - (correctedLeft + 225);
		$('.datepicker').css({'top': correctedTop + 'px','right': correctedRight + 'px','display': 'block', 'left':correctedLeft + 'px'});
	});

	$(document).on('click', '#uploadAd', function(e) {
		e.preventDefault();
		var insertMedia = wp.media.controller.Library.extend({
			defaults :  _.defaults({
				id:        'insert-ad',
				title:      'اختر المادة الإعلانية',
				allowLocalEdits: true,
				displaySettings: false,
				displayUserSettings: false,
				multiple : false,

			}, wp.media.controller.Library.prototype.defaults )
		});
		var frame = wp.media({
			button : { text : 'موافق' },
			state : 'insert-ad',
			states : [
				new insertMedia()
			]
		});

		frame.on( 'select',function() {
			var state = frame.state('insert-ad');
			var selection = state.get('selection');
			var html;
			var adType;
			var imageArray = [];
			if ( ! selection ) return;
			selection.each(function(attachment) {
				var display = state.display( attachment ).toJSON();
				var obj_attachment = attachment.toJSON()
				display = wp.media.string.props( display, obj_attachment );
				var options = {
					id:        obj_attachment.id,
					post_content: obj_attachment.description,
				};
				if ( display.linkUrl )
					options.url = display.linkUrl;
				if ( 'image' === obj_attachment.type ) {
					html = '<img src="'+obj_attachment.url+'" alt="..." class="img-thumbnail">';
					adType = 1;
				} else if ( 'video' === obj_attachment.type ) {
					html = '<video width="100%" height="auto" class="img-thumbnail" controls>'+
								'<source src="'+obj_attachment.url+'">'+
								'Your browser does not support the video tag.'+
							'</video>';
					adType = 2;
				} else {
					html = wp.media.string.link( display );
					options.post_title = display.title;
					adType = 0;
				}
				attachment.attributes['nonce'] = wp.media.view.settings.nonce.sendToEditor;
				attachment.attributes['attachment'] = options;
				attachment.attributes['html'] = html;
				attachment.attributes['post_id'] = wp.media.view.settings.post.id;

				$('#Ad_link').val(attachment.attributes['url']);
				$('#Ad_link_type').val(adType);
				$('#Ad_preview').removeClass('hidden').html(attachment.attributes['html']);
				$('#uploadAd').addClass('hidden');
				$('#AdRemoveChangeGroup').removeClass('hidden');
			});
		});

		frame.open();
		return false;
	});

	$('.bootstrap-table').on('mouseover','#advertiser-panel', function(e){
		//console.log($(this));
		$(this).children('.panel-footer').slideDown();
	});
	$('.bootstrap-table').on('mouseleave','#advertiser-panel', function(e){
		//console.log('out');
		$(this).children('.panel-footer').slideUp();
	});

	$('#clearUploadAd').on('click',function(){
		$('#Ad_link').val('');
		$('#Ad_link_type').val('');
		$('#Ad_preview').addClass('hidden').html('');
		$('#uploadAd').removeClass('hidden');
		$('#AdRemoveChangeGroup').addClass('hidden');
	});
	$('#adsFormSubmit').on('click',function(e){
		e.preventDefault();
		$("form#AdForm").submit();
	});

	$('#Ad_show_to_captain2').on('click', function(e){
		$('#capOpt').slideUp();
		$('#Ad_cap_view_no').val('0');
		$("#Ad_showonmap_captain2").prop("checked", true);
		$("#Ad_cap_notify2").prop("checked", true).prop("disabled",true);
		$("#Ad_cap_notify1").prop("disabled",true);
	});
	$('#Ad_show_to_captain1').on('click', function(e){
		$('#capOpt').slideDown();
	});
	$('#Ad_show_to_user2').on('click', function(e){
		$('#userOpt').slideUp();
		$('#Ad_user_view_no').val('0');
		$("#Ad_user_notify2").prop("checked", true);
		$("#Ad_showonmap_user2").prop("checked", true).prop("disabled",true);
		$("#Ad_showonmap_user1").prop("disabled",true);
	});
	$('#Ad_show_to_user1').on('click', function(e){
		$('#userOpt').slideDown();
	});
	$('#Ad_showonmap_captain2').on('click',function(){
		$("#Ad_cap_notify2").prop("checked", true).prop("disabled",true);
		$("#Ad_cap_notify1").prop("disabled",true);
	});
	$('#Ad_showonmap_captain1').on('click',function(){
		$("#Ad_cap_notify2").prop("disabled",false);
		$("#Ad_cap_notify1").prop("disabled",false);
	});
	$('#Ad_user_notify2').on('click',function(){
		$("#Ad_showonmap_user2").prop("checked", true).prop("disabled",true);
		$("#Ad_showonmap_user1").prop("disabled",true);
	});
	$('#Ad_user_notify1').on('click',function(){
		$("#Ad_showonmap_user2").prop("disabled",false);
		$("#Ad_showonmap_user1").prop("disabled",false);
	});
	$("form#AdForm").submit(function(){
		var submit = $("#adsFormSubmit");
		var message	= $("#msgBox");
		if(submit.hasClass('disabled')){
			message.html('<div class="alert alert-danger fade in">'+
					'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+
			'<p class="login-msg">بعض المدخلات فارغة أو غير صحيحة</p></div>');
			return;
		}

		var contents = {
				action: 				'AdFormSubmit',
				nonce: 					this.AdFormSubmit_nonce.value,
				Ad_id: 					this.Ad_id.value,
				Ad_ar_name:				this.Ad_ar_name.value,
				Ad_en_name:				this.Ad_en_name.value,
				Ad_type:				this.Ad_type.value,
				Ad_priority:			this.Ad_priority.value,
				Ad_start_date:			this.Ad_start_date.value,
				Ad_end_date:			this.Ad_end_date.value,
				Ad_link_type:			this.Ad_link_type.value,
				Ad_link:				this.Ad_link.value,
				Ad_locations:			this.Ad_locations.value,
				Ad_show_to_captain:		this.Ad_show_to_captain.value,
				Ad_cap_notify:			this.Ad_cap_notify.value,
				Ad_showonmap_captain:	this.Ad_showonmap_captain.value,
				Ad_cap_view_no:			this.Ad_cap_view_no.value,
				Ad_show_to_user:		this.Ad_show_to_user.value,
				Ad_user_notify:			this.Ad_user_notify.value,
				Ad_showonmap_user:		this.Ad_showonmap_user.value,
				Ad_user_view_no:		this.Ad_user_view_no.value,
				Advertiser_name:		this.Advertiser_name.value,
				Advertiser_type:		this.Advertiser_type.value,
				Advertiser_phone:		this.Advertiser_phone.value,
				Advertiser_email:		this.Advertiser_email.value,
				Advertiser_address:		this.Advertiser_address.value,
				Advertiser_website:		this.Advertiser_website.value,
				Advertiser_rep_name:	this.Advertiser_rep_name.value,
				Advertiser_rep_type:	this.Advertiser_rep_type.value,
				Advertiser_rep_phone:	this.Advertiser_rep_phone.value,
				Advertiser_rep_email:	this.Advertiser_rep_email.value,
		};


		submit.attr("disabled", "disabled").addClass('btn-warning disabled');
		var submit_value = submit.html();
		submit.html("<i class='fa fa-cog fa-spin'></i>");

		$.post( admin_ajax.url, contents, function( data ){
			submit.removeAttr("disabled").removeClass('btn-warning disabled');
			submit.html(submit_value);
			if( data.success == 1) {
				message.html(data.msg);
				if(data.action == 'insert'){
					$('#Ad_id').val(data.id).attr('value',data.id);
					var url = new Url;
					url.query['id'] = data.id;
					window.history.pushState('', '', url.toString());
				}
			} else {
				message.html(data.msg);
			}
		}, 'json');
		return false;
	});

	$('#table').on('click', '#deleteAd', function(e){
		e.preventDefault();
		var dataId = $(this).attr('data-id');
		//console.log(dataId);
		var contents = {
			action:	'admineDeleteAd',
			nonce:	$('#TableData_nonce').val(),
			Ad_id: dataId,
		};
		var conf = window.confirm("هل تريد حذف هذا الإعلان");
		if(conf == true){
			$.post( admin_ajax.url, contents, function(data){
				if(data.success == 1){
					//console.log(dataId);
					tableElement.bootstrapTable('remove', {
						field: 'Ad_id',
						values: dataId
					});
					tableElement.bootstrapTable('refresh');
				}
			}, 'json');
		}
	});
	var manageTable = $('.bootstrap-table');
	manageTable.on('click', '#btn-map' , function(e){
		$($(this).attr('data-target')).modal("show");
	});
	manageTable.on('shown.bs.modal', '.map-modal', function (e) {
		var location = JSON.parse($(e.currentTarget).attr('data-location'));
		var element = $(e.currentTarget).attr('data-map')
		myMap(element, location);
	});
	manageTable.on('click', '#btn-stat', function(e){
		var modalTag = $(this);
		var _adId = $(this).attr('data-adid');
		var contents = {
			action:	'singleAdStat',
			ad_id: _adId,
		};

		$.post(admin_ajax.url, contents,function(data){
			console.log(data.result);
			$(modalTag.attr('data-target')).attr('data-loc', JSON.stringify(data.result));
			$('#statData'+_adId).html(data.stats);
			$(modalTag.attr('data-target')).modal("show");
		}, 'json');
	});
	manageTable.on('shown.bs.modal', '.stat-modal', function (e) {
		var location = JSON.parse($(e.currentTarget).attr('data-loc'));
		var element = $(e.currentTarget).attr('data-map')
		console.log(location,element);
		myStatMap(element, location);
	});
	$('.cap-item').on('click',function(e){
		e.preventDefault();
		$('.cap-item').each(function(){
			$(this).removeClass('active');
		});
		$(this).addClass('active')
	});
    $('#adsFilterBtn').on('click',function(){
        var capName = $('.cap-item.active').attr('data-cap');
        var startDate = $('#filterStartDate').val();
        var endDate = $('#filterEndDate').val();
        var _event = $('#event').val();
        var contents = {
            action:	'selectCapStat',
            capname: capName,
            startdate: startDate,
            enddate: endDate,
            event: _event,
        };

        $.post(admin_ajax.url, contents,function(data){
            //console.log(data.result);
            $('#remoteData').html(data.result);
        }, 'json');
    });

	$(".date-group-stat").datepicker({
		autoclose: true,
		format: 'yyyy-mm-dd',
		todayBtn: false,
		todayHighlight: true,
		orientation: 'left bottom',
		clearBtn: false,
		inputs: $('[data-statdate]'),
	});
	$('[data-statdate]').datepicker().on('show', function(e) {
		var correctedLeft = $(e.currentTarget).offset().left;
		var correctedTop = $(e.currentTarget).offset().top + 38;
		var correctedRight = $(window).width() - (correctedLeft + 225);
		$('.datepicker').css({'top': correctedTop + 'px','right': correctedRight + 'px','display': 'block', 'left':correctedLeft + 'px'});
	});

    $('#event').on('change',function(e){
        //console.log(e, this);
        if($('#event').val() == "captain_update"){
            $('#filterEndDate').datepicker('update', $('#filterStartDate').val());
        }
        $(".date-group-stat").datepicker('updateDates');
    });

    $('#filterStartDate').datepicker().on('hide changeDate',function(e){
        if($('#event').val() == "captain_update"){
            $('#filterEndDate').datepicker('update', $('#filterStartDate').val());
            $(".date-group-stat").datepicker('updateDates');
        }
    });
    $('#filterEndDate').datepicker().on('hide changeDate',function(e){
        if($('#event').val() == "captain_update") {
            $('#filterStartDate').datepicker('update', $('#filterEndDate').val());
            $(".date-group-stat").datepicker('updateDates');
        }
    });

    $('#userAdsFilterBtn').on('click',function(){
        var startDate = $('#userfilterStartDate').val();
        var endDate = $('#userfilterEndDate').val();
        var _event = $('#user_event').val();
        var contents = {
            action:	'selectUserStat',
            startdate: startDate,
            enddate: endDate,
            event: _event,
        };

        $.post(admin_ajax.url, contents,function(data){
            //console.log(data.result);
            $('#remoteUserData').html(data.result);
        }, 'json');
    });

	$("form#paid-form").submit(function(){
		var submit = $("#paid-btn");
		var message	= $("#msgBox");
		//if(submit.hasClass('disabled')){
		//	message.html('<div class="alert alert-danger fade in">'+
		//			'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+
		//			'<p class="login-msg">بعض المدخلات فارغة أو غير صحيحة</p></div>');
		//	return;
		//}

		var contents = {
			action: 				'adPaidSubmit',
			nonce: 					this.adPriceSubmit_nonce.value,
			ad_id: 					this.ad_id.value,
			paidtxt: 				this.paidtext.value,
		};


		//submit.attr("disabled", "disabled").addClass('btn-warning disabled');
		//var submit_value = submit.html();
		//submit.html("<i class='fa fa-cog fa-spin'></i>");

		$.post( admin_ajax.url, contents, function( data ){
			console.log(contents);
			submit.removeAttr("disabled").removeClass('btn-warning disabled');
			submit.html(submit_value);
			if( data.success == 1) {
				message.html(data.msg);
				if(data.action == 'insert'){
					$('#Ad_id').val(data.id).attr('value',data.id);
					var url = new Url;
					url.query['id'] = data.id;
					window.history.pushState('', '', url.toString());
				}
			} else {
				message.html(data.msg);
			}
		}, 'json');
		return false;
	});
});
