;(function ( $, window, document, undefined ) {
  'use strict';
  
	tinymce.create('tinymce.plugins.divilibraryshortcodes', {
		
		init : function(ed, url) {
			
			ed.addButton('divilifeshortcode', {
				title : dls_strings.divilifeshortcode,
				image : url + '/../../images/admin/icon-divilifeshortcode.gif',
				onclick : function() {
					openModalDiviLifeShortcode('divilifeshortcode');
				}
			});
		},
		
		createControl : function(n, cm) {
			return null;
		},
	});
		
	tinymce.PluginManager.add( 'divilifeshortcodes', tinymce.plugins.divilibraryshortcodes );
	
	
	function formatPostResults ( post ) {
		
		var post_title = formatPostTitle( post );
		
		if ( post.loading ) {
			return post.text;
		}
		
		if ( typeof post_title === 'undefined' ) {
			post_title = 'Page without Title';
		}
		
		var markup = "<div class='select2-result-post clearfix'>" +
		"<div class='select2-result-post__meta'>" +
		  "<div class='select2-result-post__title'>" + post.id + " : " + post_title + "</div>";
		  
		markup += "</div></div>";
		
		return markup;
	}
	
	
	function formatPostTitle (post) {
		return post.post_title || post.text;
	}
	
	
	function openModalDiviLifeShortcode( tag ){

		var outputOptions = '',
			index = tag;

			for (var index2 in dls_settings[index]) {
				
				outputOptions += '<tr>\n';
				outputOptions += '<th><label for="dls-' + index2 + '">'+ dls_settings[index][index2]['name'] +'</label></th>\n';
				outputOptions += '<td>';
				
				if (dls_settings[index][index2]['type'] === 'select') {
					var optionsArray = dls_settings[index][index2]['options'].split('|');

					outputOptions += '\n<select name="dls-' + index2 + '" id="dls-' + index2 + '" data-defaultvalue="" data-placeholder="' + dls_settings[index][index2]['description'] + '">\n';
					
					for (var index3 in optionsArray) {
						outputOptions += '<option value="' + optionsArray[index3] + '">' + optionsArray[index3] + '</option>\n';
					}

					outputOptions += '</select>\n';
				}
				
				outputOptions += '\n</td>';
				outputOptions += '\n</tr>';
			}


		var width = jQuery(window).width(),
			tbHeight = jQuery(window).height(),
			tbWidth = ( 720 < width ) ? 720 : width;

		tbWidth = tbWidth - 80;
		tbHeight = tbHeight - 84;
		
		var tbOptions = "<div id='dls_divilifeshortcode_div'><form id='dls_divilifeshortcode'><table id='shortcodes_table' class='form-table dls-" + tag + "'>";
		tbOptions += outputOptions;
		tbOptions += '</table>\n<p class="submit">\n<input type="button" id="dls-submit" class="button-primary" value="Ok" name="submit" /></p>\n</form></div>';

		var form = jQuery( tbOptions );

		var table = form.find('table');
		form.appendTo('body').hide();

		form.find('#dls-submit').click(function(){

			var shortcode = '[' + tag,
				$dls_form = jQuery('form#dls_divilifeshortcode'),
				id = $dls_form.find('#dls-id').val();
				
			shortcode += " id='" + id + "']\n";
			
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode + ' ');

			tb_remove();
		});
		
		
		$('select#dls-id').select2({
			dropdownParent: $('#dls_divilifeshortcode'),
			width: '100%',
			theme: "bootstrap",
			ajax: {
				url: ajaxurl,
				dataType: 'json',
				delay: 250,
				method: 'POST',
				data: function (params) {
				  return {
					action: 'ajax_dls_listposts',
					q: params.term,
					page: params.page,
					json: 1
				  };
				},
				processResults: function (data, params) {
				  params.page = params.page || 1;
				  
				  return {
					results: data.items,
					pagination: {
					  more: (params.page * 7) < data.total_count
					}
				  };
				},
				cache: true
			},
			allowClear: true,
			minimumInputLength: 1,
			escapeMarkup: function (markup) { return markup; },
			templateResult: formatPostResults,
			templateSelection: formatPostTitle
		});
		
		
		tb_show( 'Divi Life ' + tag, '#TB_inline?width=' + tbWidth + '&height=' + tbHeight + '&inlineId=dls_divilifeshortcode_div' );
		jQuery('#dls_divilifeshortcode_div').remove();
		outputOptions = '';
	}
	
})( jQuery, window, document );