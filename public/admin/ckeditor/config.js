/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For complete reference see:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

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
		{ name: 'about' },
		{ name: 'pbckcode' },
		{ name: 'uploadfile' }
	];

	// Remove some buttons provided by the standard plugins, which are
	// not needed in the Standard(s) toolbar.
	config.removeButtons = 'Underline,Subscript,Superscript';

	// Set the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';

	// Simplify the dialog windows.
	config.removeDialogTabs = 'image:advanced;link:advanced';

	config.entities = false;
	config.entities_latin = false;
	//config.basicEntities = false;
	//config.entities_processNumerical = 'force';

	config.extraPlugins = 'pbckcode,notification,notificationaggregator,filetools,lineutils,widget,widgetselection,uploadwidget,uploadfile,uploadimage';

	// uploadfile,uploadimage CUSTOMIZATION
	config.uploadUrl = '/uploader/upload.php';
	config.imageUploadUrl = '/uploader/upload.php?type=Images';

	// PBCKCODE CUSTOMIZATION
	config.pbckcode = {
		
		// An optional class to your pre tag.
		cls: '',

    	// The syntax highlighter you will use in the output view
    	highlighter: 'PRISM',

    	// An array of the available modes for you plugin.
    	// The key corresponds to the string shown in the select tag.
    	// The value correspond to the loaded file for ACE Editor.
    	modes: [['HTML', 'html'], ['CSS', 'css'], ['PHP', 'php'], ['JS', 'javascript']],

    	// The theme of the ACE Editor of the plugin.
    	theme: 'textmate',

    	// Tab indentation (in spaces)
    	tab_size: '4'
    };
};
