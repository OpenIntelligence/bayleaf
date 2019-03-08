( function( $ ) {
	// Add event triggers to the show/hide items.
	$('#widgets-right').on('change', 'select.bayleaf-post-type', function() {
		showPosttypeContent( $(this) );
	});

	$('#widgets-right').on('change', 'select.bayleaf-taxonomy', function() {
		showTerms( $(this) );
	});
	
	function showPosttypeContent( pType ) {
		var postType  = pType.val(),
			parent    = pType.parent(),
			taxSelec  = parent.nextAll( '.post-panel' ).find( 'select.bayleaf-taxonomy' );

		if ( ! postType ) {
			parent.nextAll( '.post-panel, .page-panel, .posts-styles, .posts-styles-grid' ).hide();
		} else if ( 'page' === postType ) {
			parent.nextAll( '.post-panel' ).hide();
			parent.nextAll( '.page-panel, .posts-styles' ).show();
			parent.nextAll( '.page-panel' ).find( '.pages-checklist li' ).show();
		} else if ( postType ) {
			parent.nextAll( '.page-panel' ).hide();
			parent.nextAll( '.post-panel' ).children( '.terms-panel' ).hide();
			taxSelec.find( 'option' ).hide();
			taxSelec.find( '.' + postType ).show();
			taxSelec.find( '.always-visible' ).show();
			taxSelec.val('');
			parent.nextAll( '.post-panel, .posts-styles' ).show();
		}
	}

	function showTerms( taxonomy ) {
		if ( taxonomy.val() ) {
			taxonomy.parent().next('.terms-panel').show();
			taxonomy.parent().next('.terms-panel').find( '.terms-checklist li' ).hide();
			taxonomy.parent().next('.terms-panel').find( '.terms-checklist .' + taxonomy.val() ).show();
		} else {
			taxonomy.parent().next('.terms-panel').hide();
		}
	}
}( jQuery ) );
