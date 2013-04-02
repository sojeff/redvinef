$(function() {

	// FIELD WATERMARKS
	$('input[type="text"].username').watermark('Username', {useNative: false});
	$('input[type="password"].password').watermark('Password', {useNative: false});
	$('input[type="password"].passwordconfirm').watermark('Confirm Password', {useNative: false});
	$('input[type="text"].firstname').watermark('First Name', {useNative: false});
	$('input[type="text"].lastname').watermark('Last Name', {useNative: false});
	$('input[type="text"].month').watermark('MM', {useNative: false});
	$('input[type="text"].day').watermark('DD', {useNative: false});
	$('input[type="text"].year').watermark('YYYY', {useNative: false});
	$('input[type="text"].city').watermark('City', {useNative: false});
	$('input[type="text"].zip').watermark('Zip Code', {useNative: false});
	$('input[type="text"].email').watermark('Email', {useNative: false});
	$('input[type="text"].emailconfirm').watermark('Re-enter Email', {useNative: false});
	$('input[type="text"].search').watermark('Find Knowledge by Entering a Keyword', {useNative: false});
	$('input[type="text"].title').watermark('Enter in the Title for this Thread', {useNative: false});
	$('input[type="text"].topic').watermark('Enter in the Topic', {useNative: false});
	$('.field-jumppad').watermark('Enter in the Title for this JumpPad', {useNative: false});
	$('.comment').watermark('Share Some Knowledge', {useNative: false});
	$('input[type="text"].link').watermark('Enter in a Link to Outside Content', {useNative: false});
	$('input[type="text"].content').watermark('Find Content within SO:KNO', {useNative: false});
	$('.field-post').watermark('Share Some Knowledge by Adding a Comment', {useNative: false});
	$('.field-persona').watermark('Find People to Join this JumpPad', {useNative: false});
	$('.field-link').watermark('http://', {useNative: false});
	$('.field-content').watermark('Connect to Content', {useNative: false});

	// LOGIN/SIGNUP TOGGLE
	$("#signup, #forgot").hide();
	
	$('a.signup').click(function() {
		$('#forgot, #login').hide();
		$('#signup').show();
	});

	$('a.forgot').click(function() {
		$('#signup, #login').hide();
		$('#forgot').show();
	});

	$('a.login, a.back').click(function() {
		$('#signup, #forgot').hide();
		$('#login').show();
	});



	// TOGGLE THREAD/CONTENT LISTS
	$(".list.threads").hide();

	$('a.threads').click(function() {
		$('a.content').removeClass('current');
		$(this).addClass('current');
		$('.list.content').hide();
		$('.list.threads').show();
	});

	$('a.content').click(function() {
		$('a.threads').removeClass('current');
		$(this).addClass('current');
		$('.list.threads').hide();
		$('.list.content').show();
	});

	// LINK/CONTENT FIELDS TOGGLE
	$('input[type="text"].link, input[type="text"].content').hide();

	$('.button-link').click(function() {
		$(this).toggleClass('active');
		$(this).parent().prev().find('input.link').toggle();
	});

	$('.button-content').click(function() {
		$(this).toggleClass('active');
		$(this).parent().prev().find('input.content').toggle();
	});

	// MODAL WINDOWS
	$('a.thread, a.jumppad').click(function (e) {
		e.preventDefault();

		// CALLBACK FUNCTION
		confirm(function () {
			window.location.href = '';
		});

		function confirm(callback) {
			$('#modal-create').modal({
				position: ["256px"],
				opacity: ["80"],
				focus: false,
				overlayId: 'modal-overlay',
				containerId: 'modal-container',
				onShow: function (dialog) {
					var modal = this;

					// IF CLICKS CREATE
					$('.create', dialog.data[0]).click(function () {
						
						// CALL CALLBACK
						if ($.isFunction(callback)) {
							callback.apply();
						}
						// CLOSE MODAL
						modal.close();
					});
				}
			});
		}
	});

	// MODAL CONTACT
	$('a.contact-us').click(function (e) {
		e.preventDefault();

		// CALLBACK FUNCTION
		confirm(function () {
			window.location.href = '';
		});

		function confirm(callback) {
			$('#modal-contact').modal({
				position: ["36px"],
				opacity: ["60"],
				focus: false,
				overlayId: 'modal-overlay',
				containerId: 'modal-container-contact',
				onShow: function (dialog) {
					var modal = this;

					// IF CLICKS CREATE
					$('.create', dialog.data[0]).click(function () {
						
						// CALL CALLBACK
						if ($.isFunction(callback)) {
							callback.apply();
						}
						// CLOSE MODAL
						modal.close();
					});
				}
			});
		}
	});

	// iFRAME MODAL
	$('a.iframe').click(function (e) {
		e.preventDefault();

		// CALLBACK FUNCTION
		confirm(function () {
			window.location.href = '';
		});

		function confirm(callback) {
			$('#modal-iframe').modal({
				opacity: ["80"],
				focus: false,
				overlayId: 'modal-overlay',
				containerId: 'modal-iframe-container',
				onShow: function (dialog) {
					var modal = this;

					// IF CLICKS CREATE
					$('.create', dialog.data[0]).click(function () {
						
						// CALL CALLBACK
						if ($.isFunction(callback)) {
							callback.apply();
						}
						// CLOSE MODAL
						modal.close();
					});
				}
			});
		}
		var modalHeight = $('#modal-iframe-container').height();
		$('#modal-iframe > iframe').height(modalHeight - 58);

	});

	// MODAL CONTAINER HEIGHT
	

	// CONNECT MODAL
	$('a.connect').click(function (e) {
		e.preventDefault();

		// CALLBACK FUNCTION
		confirm(function () {
			window.location.href = '';
		});

		function confirm(callback) {
			$('#modal-connect').modal({
				position: ["256px"],
				opacity: ["80"],
				focus: false,
				overlayId: 'modal-overlay',
				containerId: 'modal-container',
				onShow: function (dialog) {
					var modal = this;

					// IF CLICKS CREATE
					$('.create', dialog.data[0]).click(function () {
						
						// CALL CALLBACK
						if ($.isFunction(callback)) {
							callback.apply();
						}
						// CLOSE MODAL
						modal.close();
					});
				}
			});
		}
	});
	
	// TOGGLE FOLLOW
	$('.button-follow a').click(function(){
			$(this).toggleClass('active');
		}
	);

	$('.button.wide.follow').click(function(){
		$(this).toggleClass('follow unfollow', function() {
			if($(this).hasClass("unfollow")) {
				$("span.label-icon").html("<span class='check'></span>Not Following");
			}
			else if($(this).hasClass("follow")) {
				$("span.label-icon").html("<span class='check'></span>Following");
			}
			
		});
	});
	
	// COMMENT TEXTAREA RESIZE
	$('.comment').autosize();
	
	// CLICK FOLLOW ALL
	$('a.followall').click(function(){
			if($('.mini-persona > .button-follow a').hasClass('active')===false){
				$('.mini-persona > .button-follow a').addClass('active');
			}
		}
	);
	
	// DEFAULT TAB ACTIVE
	//$('.date').addClass('active');
	
	
	// SORT MENU TABS
	$('.date').click(function(){
			if($(this).hasClass('active')===false){
				$('#nav-sort > li > a').removeClass('active');
				$(this).addClass('active');
			}
		}
	);
	
	$('.new').click(function(){
			if($(this).hasClass('active')===false){
				$('#nav-sort > li > a').removeClass('active');
				$(this).addClass('active');
			}
		}
	);
	
	$('.alpha').click(function(){
			if($(this).hasClass('active')===false){
				$('#nav-sort > li > a').removeClass('active');
				$(this).addClass('active');
			}
		}
	);
	
	$('.relevance').click(function(){
			if($(this).hasClass('active')===false){
				$('#nav-sort > li > a').removeClass('active');
				$(this).addClass('active');
			}
		}
	);

	$('a.followme').click(function(){
			if($(this).hasClass('active')===false){
				$('#nav-connections > li > a').removeClass('active');
				$(this).addClass('active');
			}
		}
	);

	$('a.followthem').click(function(){
			if($(this).hasClass('active')===false){
				$('#nav-connections > li > a').removeClass('active');
				$(this).addClass('active');
			}
		}
	);
	
	// CURATE MENU TABS
	$('a.like').click(function(){
			if($(this).hasClass('active')===false){
				$('a.flag').removeClass('active');
				$(this).addClass('active');
			}
			else {
				$(this).removeClass('active');
			}
		}
	);
	
	$('a.flag').click(function(){
			if($(this).hasClass('active')===false){
				$('a.like').removeClass('active');
				$(this).addClass('active');
			}
			else {
				$(this).removeClass('active');
			}
		}
	);

	$("#form-tags").hide();
	var setHeight = $('#modal-iframe > iframe').height();

	var tagger = function() {
		var tagsHeight = '138';
		var frameHeight = $('#modal-iframe > iframe').height();
		var minHeight = frameHeight - tagsHeight;
		var maxHeight = frameHeight + tagsHeight;
		$(this).toggleClass("active");
		$("#form-tags").slideToggle();
		$('#modal-iframe > iframe').animate({height: minHeight}, 400);
	};
	
	$("a.tag").click(tagger);
	
	// COMMENT MENU TABS
	$('.nav-comment > .like').click(function(){
			if($(this).hasClass('active')===false){
				$('.nav-comment > li > a').removeClass('active');
				$(this).addClass('active');
			}
		}
	);
	
	$('.nav-comment > .flag').click(function(){
			if($(this).hasClass('active')===false){
				$('.nav-comment > li > a').removeClass('active');
				$(this).addClass('active');
			}
		}
	);
	
	$('.nav-comment > .reply').click(function(){
			if($(this).hasClass('active')===false){
				$('.nav-comment > li > a').removeClass('active');
				$(this).addClass('active');
			}
		}
	);
	
	$('.nav-comment > .share').click(function(){
			if($(this).hasClass('active')===false){
				$('.nav-comment > li > a').removeClass('active');
				$(this).addClass('active');
			}
		}
	);

	// PERSONA CHARTS
	$('.cht-knowledge').easyPieChart({
		barColor: '#d97023',
		trackColor: '#f2f2f2',
		scaleColor: false,
		lineCap: 'butt',
		lineWidth: 40,
		size: 366,
		animate: 1000
	});

	$('.cht-interest').easyPieChart({
		barColor: '#2a8ecb',
		trackColor: '#f2f2f2',
		scaleColor: false,
		lineCap: 'butt',
		lineWidth: 40,
		size: 276,
		animate: 1000
	});

});