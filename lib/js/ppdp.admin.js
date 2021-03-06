//********************************************************************************************************************************
// now start the engine
//********************************************************************************************************************************
jQuery(document).ready( function($) {

//********************************************************************************************************************************
// set variables
//********************************************************************************************************************************
	var pkAct;

//********************************************************************************************************************************
// quick helper to check for an existance of an element
//********************************************************************************************************************************
	$.fn.divExists = function(callback) {
		// slice some args
		var args = [].slice.call( arguments, 1 );
		// check for length
		if ( this.length ) {
			callback.call( this, args );
		}
		// return it
		return this;
	};

//********************************************************************************************************************************
// hide our existing date field.
//********************************************************************************************************************************
	$( 'div#misc-publishing-actions' ).divExists( function() {
	//	$( '.misc-pub-curtime' ).remove();

	});

//********************************************************************************************************************************
// expose our pickers when clicked.
//********************************************************************************************************************************
	$( '.misc-pub-pickerdatetime' ).on( 'click', '.edit-pickerstamp', function( event ) {

		// keep my UI clean
		event.preventDefault();

		// Hide my edit button.
		$( '.edit-pickerstamp' ).hide();

		// Show the fields.
		$( '.pickerstamp-fieldset' ).slideDown( 'fast' );
	});

//********************************************************************************************************************************
// collapse our pickers when clicked.
//********************************************************************************************************************************
	$( '.misc-pub-pickerdatetime' ).on( 'click', '.action-pickerstamp', function( event ) {

		// keep my UI clean
		event.preventDefault();

		// Get my picker action.
		pkAct   = $( this ).data( 'action' );

		// Show my edit button regardless.
		$( '.edit-pickerstamp' ).show();

		// If we are canceling, then just bail.
		if ( 'cancel' == pkAct ) {
			$( '.pickerstamp-fieldset' ).slideUp( 'fast' );
			return;
		}
	});

//********************************************************************************************************************************
//  load datepicker
//********************************************************************************************************************************
	/*
	$( '.mefa-meta-datepicker' ).each( function() {
		$( this ).find( 'input.meta-entry-date' ).pickmeup({
			position		: 'top',
			hide_on_select	: true,
			format  		: 'm/d/Y'
		});
	});
	*/

//********************************************************************************************************************************
//  load timepickers
//********************************************************************************************************************************
	/*
	$( '.mefa-meta-timepicker' ).each(function() {
		$( this ).find( 'input.meta-entry-time' ).timepicker({
			'scrollDefault':	'now',
			'step':				15,
			'disableTimeRanges': [
				[ '12am', '5am' ],
				[ '10pm', '11:59pm' ]
			]
		});
	});
	*/
//********************************************************************************************************************************
// that's all folks. we're done here
//********************************************************************************************************************************
});
