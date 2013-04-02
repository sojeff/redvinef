$(function() {

	// FIELD WATERMARKS
	$('input[type="text"].username').watermark('Username', {useNative: false});
	$('input[type="password"].password').watermark('Password', {useNative: false});
	$('input[type="text"].firstname').watermark('First Name', {useNative: false});
	$('input[type="text"].lastname').watermark('Last Name', {useNative: false});
	$('input[type="text"].email').watermark('Email', {useNative: false});
	$('input[type="text"].emailconfirm').watermark('Re-enter Email', {useNative: false});
	$('input[type="text"].search').watermark('Find Knowledge by Entering a Keyword', {useNative: false});
	$('input[type="text"].title').watermark('Enter in the Title for this Thread', {useNative: false});
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
	
	$('a.signup, a.login').click(function() {
		$('#signup, #login').toggle();
	});

	$('a.forgot, a.back').click(function() {
		$('#forgot, #login').toggle();
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

	// PERSONA CHARTS
	$('.persona-chart').easyPieChart({
		barColor: '#d97023',
		trackColor: '#c7cbcd',
		scaleColor: false,
		lineCap: 'butt',
		lineWidth: 50,
		size: 366,
		animate: 1000
	});

	$('.updateEasyPieChart').on('click', function(e) {
		e.preventDefault();
		
		$('.persona-chart').each(function() {
			var newValue = Math.round(100*Math.random());
			$(this).data('easyPieChart').update(newValue);
			$('span', this).text(newValue);
		});
	});


	// MODAL WINDOWS
	$('a.thread, a.jumppad').click(function (e) {
		e.preventDefault();

		// CALLBACK FUNCTION
		confirm(function () {
			window.location.href = '';
		});
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
	
	// TOGGLE FOLLOW
	$('.button-follow a').click(function(){
			$(this).toggleClass('active');
		}
	);

	$('.button.wide').click(function(){
			$(this).toggleClass('follow unfollow', function() {
				if($(this).hasClass("unfollow")) {
					$("span.label-icon").html("<span class='check'></span>Not Following");
				}
				else if($(this).hasClass("follow")) {
					$("span.label-icon").html("<span class='check'></span>Following");
				}
				
			});	
		}
	);
	
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
	
	// CURATE MENU TABS
	$('.like').click(function(){
			if($(this).hasClass('active')===false){
				$('#nav-curate > li > a').removeClass('active');
				$(this).addClass('active');
			}
		}
	);
	
	$('.flag').click(function(){
			if($(this).hasClass('active')===false){
				$('#nav-curate > li > a').removeClass('active');
				$(this).addClass('active');
			}
		}
	);
	
	$('.tag').click(function(){
			if($(this).hasClass('active')===false){
				$('#nav-curate > li > a').removeClass('active');
				$(this).addClass('active');
			}
		}
	);
	
	$('.share').click(function(){
			if($(this).hasClass('active')===false){
				$('#nav-curate > li > a').removeClass('active');
				$(this).addClass('active');
			}
		}
	);
	
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
	
	
});