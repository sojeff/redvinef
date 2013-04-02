jQuery(document).ready( function(){
	//nav hover chain

	//bindings 
	$('.follow').click(function(){
			if($(this).hasClass('yes')){
				$(this).removeClass('yes');
			} else {
				$(this).addClass('yes');
			}
		}
	);
	$('.sort-content').click(function(){
			if($(this).hasClass('on')){
			} else {
				$(this).addClass('on');
				$('.sort-threads').removeClass('on');
			}
		}
	);
	/*$('span.label').click(function(){
			if($(this).hasClass('on')){
			} else {
				$(this).addClass('on');
				$('.sort-content').removeClass('on');
			}
		}
	);*/
	$('.sort-threads').click(function(){
			if($(this).hasClass('on')){
			} else {
				$(this).addClass('on');
				$('.sort-content').removeClass('on');
			}
		}
	);
	
	$('#gridBtn1').click(function(){
			if($(this).parent().hasClass('arrangement1')===false){
				$(this).parent().removeClass('arrangement2 arrangement3 arrangement4').addClass('arrangement1');
			}
		}
	);
	//activate this(below -> #userCredentials a click binding
	/*$('#userCredentials a').click(function(){
			if($(this).hasClass('on')){
				$(this).removeClass('on');
			} else{
				$(this).addClass('on');
			}
		}
	);*/
	$('#gridBtn2').click(function(){
			if($(this).parent().hasClass('arrangement2')===false){
				$(this).parent().removeClass('arrangement1 arrangement3 arrangement4').addClass('arrangement2');
			}
		}
	);
	$('#gridBtn3').click(function(){
			if($(this).parent().hasClass('arrangement3')===false){
				$(this).parent().removeClass('arrangement2 arrangement1 arrangement4').addClass('arrangement3');
			}
		}
	);
	$('#gridBtn4').click(function(){
			if($(this).parent().hasClass('arrangement4')===false){
				$(this).parent().removeClass('arrangement2 arrangement3 arrangement1').addClass('arrangement4');
			}
		}
	);
	$(".nav1").click(function(){
			if($(this).hasClass('up')===false){
				$('.nav1').removeClass('down').addClass('up');
				$('.nav2').removeClass('up').addClass('down');
				$('.nav3').removeClass('up').addClass('down');
			}
		}
	);
	$(".nav2").click(function(){
			if($(this).hasClass('up')===false){
				$('.nav2').removeClass('down').addClass('up');
				$('.nav1').removeClass('up').addClass('down');
				$('.nav3').removeClass('up').addClass('down');
			}
		}
	);
	$(".nav3").click(function(){
			if($(this).hasClass('up')===false){
				$('.nav3').removeClass('down').addClass('up');
				$('.nav1').removeClass('up').addClass('down');
				$('.nav2').removeClass('up').addClass('down');
			}
		}
	);
	
	
	$('.follow-all').click(function(){
			if($(this).hasClass('on')){
				$(this).removeClass('on');
			} else {
				$(this).addClass('on');
			}
		}
	);

    /* Run clearTextField function on all selected text fields */
	clearTextField( jQuery(":input") );
});

/**
 * Clears the default value on the text input field when the user clicks on it.
 * Also restores the default value if the field is left blank.
*/
function clearTextField( field ){

	field.focus( function(){
		jQuery(this).addClass('active');

		if ( this.value == this.defaultValue ){
			this.value = '';
		}
	});

	field.blur( function(){
		jQuery(this).removeClass('active');
		if( this.value == '' ){
			this.value = this.defaultValue;
		}
	});
}