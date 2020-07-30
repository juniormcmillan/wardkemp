/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */
/*
 CKEDITOR.editorConfig = function(config) {
 // ...

 config.allowedContent = true;


 //	config.skin = 'moono';
 //	config.toolbar = 'Full';
 //	config.removePlugins = "elementspath";
 //	config.resize_enabled = false;
 //	config.extraPlugins = 'fontawesome,lineutils,widget,image2,doksoft_bootstrap_include,doksoft_bootstrap_advanced_blocks,doksoft_bootstrap_block_conf,doksoft_bootstrap_templates,doksoft_bootstrap_table,doksoft_bootstrap_button,doksoft_bootstrap_icons,doksoft_bootstrap_gallery,doksoft_bootstrap_badge,doksoft_bootstrap_label,doksoft_bootstrap_breadcrumbs,doksoft_bootstrap_alert';

 //	config.extraPlugins = "simpleuploads,doksoft_bootstrap_include,doksoft_bootstrap_advanced_blocks,doksoft_bootstrap_block_conf,doksoft_bootstrap_templates,doksoft_bootstrap_table,doksoft_bootstrap_button,doksoft_bootstrap_icons,doksoft_bootstrap_gallery,doksoft_bootstrap_badge,doksoft_bootstrap_label,doksoft_bootstrap_breadcrumbs,doksoft_bootstrap_alert";

 //	config.extraPlugins = 'simpleuploads,doksoft_bootstrap_templates,doksoft_bootstrap_include,doksoft_bootstrap_table_new,doksoft_bootstrap_table_conf,doksoft_bootstrap_table_row_conf,doksoft_bootstrap_table_col_conf,doksoft_bootstrap_table_cell_conf,doksoft_bootstrap_table_row_move_up,doksoft_bootstrap_table_row_move_down,doksoft_bootstrap_table_col_move_left,doksoft_bootstrap_table_col_move_right,doksoft_bootstrap_table_add_row_up,doksoft_bootstrap_table_add_row_down,doksoft_bootstrap_table_add_col_left,doksoft_bootstrap_table_add_col_right,doksoft_bootstrap_table_add_cell_left,doksoft_bootstrap_table_add_cell_right,doksoft_bootstrap_table_delete_col,doksoft_bootstrap_table_delete_row,doksoft_bootstrap_table_delete_cell,doksoft_bootstrap_table_merge_cells,doksoft_bootstrap_table_merge_cell_right,doksoft_bootstrap_table_merge_cell_down,doksoft_bootstrap_table_split_cell_hor,doksoft_bootstrap_table_split_cell_vert';

 config.extraPlugins = "simpleuploads,doksoft_bootstrap_include,doksoft_bootstrap_advanced_blocks,doksoft_bootstrap_block_conf,doksoft_bootstrap_templates,doksoft_bootstrap_table_new,doksoft_bootstrap_button,doksoft_bootstrap_icons,doksoft_bootstrap_gallery,doksoft_bootstrap_badge,doksoft_bootstrap_label,doksoft_bootstrap_breadcrumbs,doksoft_bootstrap_alert,doksoft_bootstrap_table_new,doksoft_bootstrap_table_conf,doksoft_bootstrap_table_row_conf,doksoft_bootstrap_table_col_conf,doksoft_bootstrap_table_cell_conf";

 //	config.toolbar = [  [ 'doksoft_bootstrap_advanced_blocks', 'doksoft_bootstrap_block_conf', 'doksoft_bootstrap_templates', 'doksoft_bootstrap_table_new', 'doksoft_bootstrap_button', 'doksoft_bootstrap_icons', 'doksoft_bootstrap_gallery', 'doksoft_bootstrap_badge', 'doksoft_bootstrap_label', 'doksoft_bootstrap_breadcrumbs', 'doksoft_bootstrap_alert' ]  ];

 //	config.toolbar = [

 //		{ name: 'about', items: [ 'About' ] },
 //		{ name: 'doksoft_bootstrap', items: ['doksoft_bootstrap_advanced_blocks', 'doksoft_bootstrap_block_conf', 'doksoft_bootstrap_templates', 'doksoft_bootstrap_table_new', 'doksoft_bootstrap_button', 'doksoft_bootstrap_icons', 'doksoft_bootstrap_gallery', 'doksoft_bootstrap_badge', 'doksoft_bootstrap_label', 'doksoft_bootstrap_breadcrumbs', 'doksoft_bootstrap_alert'] },
 //	];

 config.toolbar_name = [  [ 'doksoft_bootstrap_advanced_blocks', 'doksoft_bootstrap_block_conf', 'doksoft_bootstrap_templates', 'doksoft_bootstrap_table_new', 'doksoft_bootstrap_button', 'doksoft_bootstrap_icons', 'doksoft_bootstrap_gallery', 'doksoft_bootstrap_badge', 'doksoft_bootstrap_label', 'doksoft_bootstrap_breadcrumbs', 'doksoft_bootstrap_alert' ]  ];


 // The toolbar groups arrangement, optimized for two toolbar rows.
 config.toolbarGroups = [
 { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
 { name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
 { name: 'links' },
 { name: 'insert' },
 { name: 'forms' },
 { name: 'tools' },
 { name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
 { name: 'others' },
 '/',
 { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
 { name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
 { name: 'styles' },
 { name: 'colors' },
 { name: 'doksoft2',	groups: ['simpleuploads','doksoft_bootstrap_table_new','doksoft_bootstrap_table_conf','doksoft_bootstrap_table_row_conf','doksoft_bootstrap_table_col_conf','doksoft_bootstrap_table_cell_conf'] },
 '/',
 { name: 'doksoft', 	groups: [ 'doksoft_bootstrap_advanced_blocks', 'doksoft_bootstrap_block_conf', 'doksoft_bootstrap_templates', 'doksoft_bootstrap_table', 'doksoft_bootstrap_button', 'doksoft_bootstrap_icons', 'doksoft_bootstrap_gallery', 'doksoft_bootstrap_badge', 'doksoft_bootstrap_label', 'doksoft_bootstrap_breadcrumbs', 'doksoft_bootstrap_alert' ] },



 ];



 config.doksoft_bootstrap_advanced_blocks_enabled_by_default	=	true;
 config.doksoft_bootstrap_use_theme = true;
 config.doksoft_bootstrap_include_in_container	=	true;
 // this fixes CSS issue with CKEDITOR CM 11-10-2016
 config.doksoft_bootstrap_include_css_to_global_doc = false;
 config.doksoft_bootstrap_include_js_to_global_doc = true;


 //	config.extraPlugins = 'simpleuploads,doksoft_bootstrap_include,doksoft_bootstrap_table_new,doksoft_bootstrap_table_conf,doksoft_bootstrap_table_row_conf';
 config.filebrowserBrowseUrl				=	'/admin/includes/fileman/index.html';
 config.filebrowserImageBrowseUrl		=	'/admin/includes/fileman/index.html';
 config.filebrowserImageUploadUrl		=	'/admin/includes/fileman/index.html';
 config.filebrowserUploadUrl				=	'/admin/includes/fileman/index.html';
 config.height = '500px';

 config.allowedContent = true;
 config.extraAllowedContent = 'style(*)[*]{*};div(*)[*]{*};li(*)[*]{*};ul(*)[*]{*}';


 //	config.shiftEnterMode = CKEDITOR.ENTER_BR;


 //	config.enterMode	=	 CKEDITOR.ENTER_P,
 config.enterMode = CKEDITOR.ENTER_BR;
 config.htmlEncodeOutput = false;
 config.entities = false;
 config.basicEntities = false;

 config.contentsCss = '/app/includes/css/style.css';
 //	config.contentsCss = ['/css/mysitestyles.css', '/css/anotherfile.css'];
 n.setRules('p',{indent:false,breakAfterOpen:false});

 };

 */

CKEDITOR.editorConfig = function (config) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';


	config.allowedContent = true;


//	config.skin = 'moono';
//	config.toolbar = 'Full';
//	config.removePlugins = "elementspath";
//	config.resize_enabled = false;
//	config.extraPlugins = 'fontawesome,lineutils,widget,image2,doksoft_bootstrap_include,doksoft_bootstrap_advanced_blocks,doksoft_bootstrap_block_conf,doksoft_bootstrap_templates,doksoft_bootstrap_table,doksoft_bootstrap_button,doksoft_bootstrap_icons,doksoft_bootstrap_gallery,doksoft_bootstrap_badge,doksoft_bootstrap_label,doksoft_bootstrap_breadcrumbs,doksoft_bootstrap_alert';

//	config.extraPlugins = "simpleuploads,doksoft_bootstrap_include,doksoft_bootstrap_advanced_blocks,doksoft_bootstrap_block_conf,doksoft_bootstrap_templates,doksoft_bootstrap_table,doksoft_bootstrap_button,doksoft_bootstrap_icons,doksoft_bootstrap_gallery,doksoft_bootstrap_badge,doksoft_bootstrap_label,doksoft_bootstrap_breadcrumbs,doksoft_bootstrap_alert";

//	config.extraPlugins = 'simpleuploads,doksoft_bootstrap_templates,doksoft_bootstrap_include,doksoft_bootstrap_table_new,doksoft_bootstrap_table_conf,doksoft_bootstrap_table_row_conf,doksoft_bootstrap_table_col_conf,doksoft_bootstrap_table_cell_conf,doksoft_bootstrap_table_row_move_up,doksoft_bootstrap_table_row_move_down,doksoft_bootstrap_table_col_move_left,doksoft_bootstrap_table_col_move_right,doksoft_bootstrap_table_add_row_up,doksoft_bootstrap_table_add_row_down,doksoft_bootstrap_table_add_col_left,doksoft_bootstrap_table_add_col_right,doksoft_bootstrap_table_add_cell_left,doksoft_bootstrap_table_add_cell_right,doksoft_bootstrap_table_delete_col,doksoft_bootstrap_table_delete_row,doksoft_bootstrap_table_delete_cell,doksoft_bootstrap_table_merge_cells,doksoft_bootstrap_table_merge_cell_right,doksoft_bootstrap_table_merge_cell_down,doksoft_bootstrap_table_split_cell_hor,doksoft_bootstrap_table_split_cell_vert';

	config.extraPlugins = "simpleuploads,doksoft_bootstrap_include,doksoft_bootstrap_advanced_blocks,doksoft_bootstrap_block_conf,doksoft_bootstrap_templates,doksoft_bootstrap_table_new,doksoft_bootstrap_button,doksoft_bootstrap_icons,doksoft_bootstrap_gallery,doksoft_bootstrap_badge,doksoft_bootstrap_label,doksoft_bootstrap_breadcrumbs,doksoft_bootstrap_alert,doksoft_bootstrap_table_new,doksoft_bootstrap_table_conf,doksoft_bootstrap_table_row_conf,doksoft_bootstrap_table_col_conf,doksoft_bootstrap_table_cell_conf";

//	config.toolbar = [  [ 'doksoft_bootstrap_advanced_blocks', 'doksoft_bootstrap_block_conf', 'doksoft_bootstrap_templates', 'doksoft_bootstrap_table_new', 'doksoft_bootstrap_button', 'doksoft_bootstrap_icons', 'doksoft_bootstrap_gallery', 'doksoft_bootstrap_badge', 'doksoft_bootstrap_label', 'doksoft_bootstrap_breadcrumbs', 'doksoft_bootstrap_alert' ]  ];

//	config.toolbar = [

//		{ name: 'about', items: [ 'About' ] },
//		{ name: 'doksoft_bootstrap', items: ['doksoft_bootstrap_advanced_blocks', 'doksoft_bootstrap_block_conf', 'doksoft_bootstrap_templates', 'doksoft_bootstrap_table_new', 'doksoft_bootstrap_button', 'doksoft_bootstrap_icons', 'doksoft_bootstrap_gallery', 'doksoft_bootstrap_badge', 'doksoft_bootstrap_label', 'doksoft_bootstrap_breadcrumbs', 'doksoft_bootstrap_alert'] },
//	];

	config.toolbar_name = [  [ 'doksoft_bootstrap_advanced_blocks', 'doksoft_bootstrap_block_conf', 'doksoft_bootstrap_templates', 'doksoft_bootstrap_table_new', 'doksoft_bootstrap_button', 'doksoft_bootstrap_icons', 'doksoft_bootstrap_gallery', 'doksoft_bootstrap_badge', 'doksoft_bootstrap_label', 'doksoft_bootstrap_breadcrumbs', 'doksoft_bootstrap_alert' ]  ];


	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'links' },
		{ name: 'insert' },
		{ name: 'forms' },
		{ name: 'tools' },
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others' },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
		{ name: 'styles' },
		{ name: 'colors' },
		{ name: 'doksoft2',	groups: ['simpleuploads','doksoft_bootstrap_table_new','doksoft_bootstrap_table_conf','doksoft_bootstrap_table_row_conf','doksoft_bootstrap_table_col_conf','doksoft_bootstrap_table_cell_conf'] },
		'/',
		{ name: 'doksoft', 	groups: [ 'doksoft_bootstrap_advanced_blocks', 'doksoft_bootstrap_block_conf', 'doksoft_bootstrap_templates', 'doksoft_bootstrap_table', 'doksoft_bootstrap_button', 'doksoft_bootstrap_icons', 'doksoft_bootstrap_gallery', 'doksoft_bootstrap_badge', 'doksoft_bootstrap_label', 'doksoft_bootstrap_breadcrumbs', 'doksoft_bootstrap_alert' ] },



	];



	config.doksoft_bootstrap_advanced_blocks_enabled_by_default	=	true;
	config.doksoft_bootstrap_use_theme = true;
	config.doksoft_bootstrap_include_in_container	=	true;
	// this fixes CSS issue with CKEDITOR CM 11-10-2016
	config.doksoft_bootstrap_include_css_to_global_doc = false;
	config.doksoft_bootstrap_include_js_to_global_doc = true;


//	config.extraPlugins = 'simpleuploads,doksoft_bootstrap_include,doksoft_bootstrap_table_new,doksoft_bootstrap_table_conf,doksoft_bootstrap_table_row_conf';
	config.filebrowserBrowseUrl				=	'/admin/includes/fileman/index.html';
	config.filebrowserImageBrowseUrl		=	'/admin/includes/fileman/index.html';
	config.filebrowserImageUploadUrl		=	'/admin/includes/fileman/index.html';
	config.filebrowserUploadUrl				=	'/admin/includes/fileman/index.html';
	config.height = '500px';

	config.allowedContent = true;

	CKEDITOR.on('instanceReady', function (ev) {
		var writer = ev.editor.dataProcessor.writer;
		// The character sequence to use for every indentation step.
		writer.indentationChars = '  ';




		var dtd = CKEDITOR.dtd;
		// Elements taken as an example are: block-level elements (div or p), list items (li, dd), and table elements (td, tbody).
		for (var e in CKEDITOR.tools.extend({}, dtd.$block, dtd.$listItem, dtd.$tableContent)) {
			ev.editor.dataProcessor.writer.setRules(e, {
				// Indicates that an element creates indentation on line breaks that it contains.
				indent: false,
				// Inserts a line break before a tag.
				breakBeforeOpen: false,
				// Inserts a line break after a tag.
				breakAfterOpen: false,
				// Inserts a line break before the closing tag.
				breakBeforeClose: false,
				// Inserts a line break after the closing tag.
				breakAfterClose: false
			});
		}

		for (var e in CKEDITOR.tools.extend({}, dtd.$list, dtd.$listItem, dtd.$tableContent)) {
			ev.editor.dataProcessor.writer.setRules(e, {
				indent: true,
			});
		}



	});
}