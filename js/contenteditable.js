/*
  You would call this when receiving a plain text
  value back from an API, and before inserting the
  text into the `contenteditable` area on a page.
*/

function cleanoutput() {
	define(function(require) {
	  'use strict';

	  /*
		Used to convert text with line breaks
		into the appropriate markup with <br>.
	  */
	  return function(value) {
		value = $.trim(value);
		value = value.replace(/\n+\s+\n+/g, '\n\n');
		value = value.replace(/\n\n+/g, '\n\n');
		value = value.replace(/\n/g, '<br>');
		value = value.replace(/\s+/g, ' ');

		return value;
	  };

	// END: define.
	});
};


function cleaninput(value) {

	/*
	  You would call this after getting an element's
	  `.innerHTML` value, while the user is typing.
	*/
	
	  /*
		Used to convert HTML text with <br> or other line
		breaks into a text string with new-line characters.
	  */
	 
		// Convert `&amp;` to `&`.
		value = value.replace(/&amp;/gi, '&');

		// Replace spaces.
		value = value.replace(/&nbsp;/gi, ' ');
		value = value.replace(/\s+/g, ' ');

		// Remove "<b>".
		value = value.replace(/<b>/gi, '');
		value = value.replace(/<\/b>/gi, '');

		// Remove "<strong>".
		value = value.replace(/<strong>/gi, '');
		value = value.replace(/<\/strong>/gi, '');

		// Remove "<i>".
		value = value.replace(/<i>/gi, '');
		value = value.replace(/<\/i>/gi, '');

		// Remove "<em>".
		value = value.replace(/<em>/gi, '');
		value = value.replace(/<\/em>/gi, '');

		// Remove "<u>".
		value = value.replace(/<u>/gi, '');
		value = value.replace(/<\/u>/gi, '');

		// Tighten up "<" and ">".
		value = value.replace(/>\s+/g, '>');
		value = value.replace(/\s+</g, '<');

		// Replace "<br>".
		value = value.replace(/<br>/gi, '\r\n');

		// Replace "<div>" (from Chrome).
		value = value.replace(/<div>/gi, '\r\n');
		value = value.replace(/<\/div>/gi, ' ');

		// Replace "<p>" (from IE).
		value = value.replace(/<p>/gi, '\r\n');
		value = value.replace(/<\/p>/gi, ' ');

		// No more than 2x newline, per "paragraph".
		value = value.replace(/\n\n+/g, '\r\n');

		// Whitespace before/after.
		value = $.trim(value);
		
		//console.log(value);

		return value;
	  


};

function cleanpaste() {

	/*
	  You would call this when a user pastes from
	  the clipboard into a `contenteditable` area.
	*/
	define(function(require) {
	  'use strict';

	  /*
		Used if the browser doesn't allow us
		to intercept the `on paste` event.
	  */
	  function fallback(e) {
		window.setTimeout(function() {
		  var el = $(e.target);
		  var value = el.text();

		  el.text(value);
		}, 16);
	  }

	  /*
		Used to change HTML into plain text,
		after an `on paste` event has fired.
	  */
	  return function(e) {
		// Used in conditional.
		var value;

		// For IE.
		if (window.clipboardData) {
		  value = window.clipboardData.getData('text');
		}
		// Other browsers.
		else {
		  value = e.originalEvent.clipboardData.getData('text/plain');
		}

		// No value?
		if (!value) {
		  // Use fallback.
		  fallback(e);

		  // Exit.
		  return;
		}

		// Prevent paste.
		e.preventDefault();

		// Insert into temp `<textarea>`, read back out.
		value = $('<textarea></textarea>').html(value).text();

		// Literal spaces.
		value = $.trim(value).replace(/[ ]+/g, ' ');

		// Mix of newlines, spaces.
		value = value.replace(/\n+\s+\n+/g, '\n\n');

		// Double newlines.
		value = value.replace(/\n\n+/g, '\n\n');

		// For IE.
		if (document.selection) {
		  // For IE8.
		  if (document.documentMode === 8) {
			value = value.replace(/\n/g, '<br>');
		  }

		  document.selection.createRange().pasteHTML(value);
		}
		// Other browsers.
		else {
		  document.execCommand('insertText', false, value);
		}
	  };

	// END: define.
	});

};
