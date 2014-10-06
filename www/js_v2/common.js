jQuery(document).ready( function($) {
	// pulse
	$('.fade').animate( { backgroundColor: '#ffffe0' }, 300).animate( { backgroundColor: '#fffbcc' }, 300).animate( { backgroundColor: '#ffffe0' }, 300).animate( { backgroundColor: '#fffbcc' }, 300);

	// show things that should be visible, hide what should be hidden
	$('.hide-if-no-js').removeClass('hide-if-no-js');
	$('.hide-if-js').hide();

	// Basic form validation
	if ( ( 'undefined' != typeof wpAjax ) && $.isFunction( wpAjax.validateForm ) ) {
		$('form.validate').submit( function() { return wpAjax.validateForm( $(this) ); } );
	}

	// Move .updated and .error alert boxes
	$('div.wrap h2 ~ div.updated, div.wrap h2 ~ div.error').addClass('below-h2');
	$('div.updated, div.error').not('.below-h2').insertAfter('div.wrap h2:first');

	// screen settings tab
	$('#show-settings-link').click(function () {
		if ( ! $('#screen-options-wrap').hasClass('screen-options-open') ) {
			$('#contextual-notif-link-wrap').addClass('invisible');
		}
		$('#screen-options-wrap').slideToggle('fast', function(){
			if ( $(this).hasClass('screen-options-open') ) {
				$('#show-settings-link').css({'backgroundImage':'url("images/screen-options-right.gif")'});
				$('#contextual-notif-link-wrap').removeClass('invisible');
				$(this).removeClass('screen-options-open');

			} else {
				$('#show-settings-link').css({'backgroundImage':'url("images/screen-options-right-up.gif")'});
				$(this).addClass('screen-options-open');
			}
		});
		return false;
	});

	// notif tab
	$('#contextual-notif-link').click(function () {
		if ( ! $('#contextual-notif-wrap').hasClass('contextual-notif-open') ) {
			$('#screen-options-link-wrap').addClass('invisible');
		}
		$('#contextual-notif-wrap').slideToggle('fast', function(){
			if ( $(this).hasClass('contextual-notif-open') ) {
				$('#contextual-notif-link').css({'backgroundImage':'url("images/screen-options-right.gif")'});
				$('#screen-options-link-wrap').removeClass('invisible');
				$(this).removeClass('contextual-notif-open');
			} else {
				$('#contextual-notif-link').css({'backgroundImage':'url("images/screen-options-right-up.gif")'});
				$(this).addClass('contextual-notif-open');
			}
		});
		return false;
	});

	// check all checkboxes
	var lastClicked = false;
	$( 'table:visible tbody .check-column :checkbox' ).click( function(e) {
		if ( 'undefined' == e.shiftKey ) { return true; }
		if ( e.shiftKey ) {
			if ( !lastClicked ) { return true; }
			var checks = $( lastClicked ).parents( 'form:first' ).find( ':checkbox' );
			var first = checks.index( lastClicked );
			var last = checks.index( this );
			var checked = $(this).attr('checked');
			if ( 0 < first && 0 < last && first != last ) {
				checks.slice( first, last ).attr( 'checked', function(){
					if ( $(this).parents('tr').is(':visible') )
						return checked ? 'checked' : '';

					return '';
				});
			}
		}
		lastClicked = this;
		return true;
	} );
	$( 'thead :checkbox, tfoot :checkbox' ).click( function(e) {
		var c = $(this).attr('checked');
		if ( 'undefined' == typeof  toggleWithKeyboard)
			toggleWithKeyboard = false;
		var toggle = e.shiftKey || toggleWithKeyboard;
		$(this).parents( 'form:first' ).find( 'table tbody:visible').find( '.check-column :checkbox' ).attr( 'checked', function() {
			if ( $(this).parents('tr').is(':hidden') )
				return '';
			if ( toggle )
				return $(this).attr( 'checked' ) ? '' : 'checked';
			else if (c)
				return 'checked';
			return '';
		});
		$(this).parents( 'form:first' ).find( 'table thead:visible, table tfoot:visible').find( '.check-column :checkbox' ).attr( 'checked', function() {
			if ( toggle )
				return '';
			else if (c)
				return 'checked';
			return '';
		});
	});
});

