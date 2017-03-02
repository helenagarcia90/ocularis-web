CKEDITOR.addTemplates( 'ocularis',
{
	// The name of the subfolder that contains the preview images of the templates.
	//imagesPath : 'images',//CKEDITOR.getUrl( CKEDITOR.plugins.getPath( 'templates' ) + 'templates/images/' ),
 
	// Template definitions.
	templates :
		[
			{
				title: '2 Columnas',
			//	image: 'columnas.jpg',
				description: '2 Columnas con imagenes arriba de cada una',
				html: '<table style="width:100%;border-collapse: collapse;border-color: transparent;">'+
					 '<tbody>'+
						'<tr>'+
							'<td style="text-align:center; width:50%">Imágen</td>'+
							'<td style="text-align:center; width:50%">Imágen</td>'+
						'</tr>'+
						'<tr>'+
							'<td>'+
							'<ul>'+
								'<li><strong>Lorem ipsum dolor sit amet consectetur adipiscing elit.</strong> Integer gravida purus quis odio luctus tempor. Nulla ut commodo augue, sit amet aliquam purus. In lacinia sed tellus at aliquet. Suspendisse consequat nibh eget mi gravida iaculis.</li>'+
							'</ul>'+
							'</td>'+
							'<td>'+
								'<li><strong>Lorem ipsum dolor sit amet consectetur adipiscing elit.</strong> Integer gravida purus quis odio luctus tempor. Nulla ut commodo augue, sit amet aliquam purus. In lacinia sed tellus at aliquet. Suspendisse consequat nibh eget mi gravida iaculis.</li>'+
							'</td>'+
						'</tr>'+
					'</tbody>'+
				'</table>'
			},
			
		]
});