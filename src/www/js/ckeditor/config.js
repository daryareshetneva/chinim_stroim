/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
        config.language = 'ru';
	// config.uiColor = '#AADC6E';
	config.toolbarGroups = [
        { name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
        { name: 'insert' },
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
        { name: 'colors' },
        { name: 'tools' },
        '/',
        { name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align' ] },
        { name: 'links' },
        { name: 'styles' },
    ];
    config.removeDialogTabs = 'image:advanced;link:advanced';
};
CKEDITOR.config.filebrowserUploadUrl = $.cfg.baseUrl + '/upload.php';
CKEDITOR.config.filebrowserImageUploadUrl = $.cfg.baseUrl + '/upload.php';