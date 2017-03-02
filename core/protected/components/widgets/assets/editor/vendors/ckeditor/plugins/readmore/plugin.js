/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @file Plugin for inserting Joomla readmore
 */

		CKEDITOR.plugins.add( 'readmore',
		{
								
				lang : 'en,es,fr,ca',
				
				init : function( editor )
				{

						// Register the toolbar buttons.
						editor.ui.addButton( 'ReadMore',
						{
								label : editor.lang.readmore.insert,
								icon : this.path + 'images/readmoreButton.gif',
								command : 'readmore'
						});
						editor.addCommand( 'readmore',
						{
								exec : function()
								{

										var divs = editor.document.getElementsByTag( 'div' );
										for ( var i = 0, len = divs.count() ; i < len ; i++ )
										{
												var div = divs.getItem( i );
												if ( div.getId() == 'system-readmore')
												{
													alert(editor.lang.readmore.error);
													return;
												}
										}
										editor.insertHtml('<div id="system-readmore"></div><p>&nbsp;</p>');
								}
						} );

				}
		});