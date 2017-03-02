<?php
/* @var $form  CActiveForm */
/* @var $model Socio */
?>


<?php if(Yii::app()->user->hasFlash('socio_success')): ?>
<div class="alert alert-success"><?= Yii::app()->user->getFlash('socio_success'); ?></div>
<?php else:?>

<div class="form">

	<h1 class="socio">Hazte socio!</h1>

	<div class="row">

	<div class="col-sm-8 col-lg-9">

		<p>
		El apoyo individual de personas como tú nos permite formar a médicos oftalmólogos y a ópticos para que sepan curar y devolver la vista a quienes más lo necesitan. Con tu aportación tú puedes devolver un vida digna a las personas que hoy en día no pueden valerse por sí solas, trabajar o ver la pizarra en su escuela. ¡Únete a OCULARIS!
		</p>
		<p>
			Tu aportación mensual nos permitirá seguir trabajando por un acceso a la salud visual universal e igualitaria y colaborarás en la reducción de la pobreza extrema en el África Subsahariana.
		</p>
		<p>
		¡Haz algo hoy extraordinario! Hazte socio de OCULARIS
		</p>

 	</div>

 	<div class="col-sm-4 col-lg-3">
 		<?=CHtml::image(Yii::app()->theme->baseUrl . '/images/socio.jpg', 'Hazte socio de Ocuilaris!', array('class' => 'img-responsive'));?>
 	</div>

 	</div>

	<?php $form = $this->beginWidget("CActiveForm");?>

	<p class="required"><?=Yii::t('SocioModule.main', '* Required fields')?></p>

	<?php echo CHtml::errorSummary($model, null, null, array('class' =>  'alert alert-danger'))?>


	<h3 class="socio">Tu aportación</h3>

	<div class="row">

		<div class="col-sm-9 col-md-6">

		<div class="form-group row">
							<?=$form->labelEx($model, 'donation', array('class' => 'col-sm-4'))?>
									<div
				class="field-group<?=$model->hasErrors('donation') ? ' error' : '';?>">
				<div class="col-sm-8">
					<div class="input-group">
										<?=$form->textField($model, 'donation', array('class' => 'text-right form-control'))?>
										 <span class="input-group-addon">€</span>
					</div>
				</div>
			</div>

		</div>

		<div class="form-group row">

				<?=$form->labelEx($model, 'donation_periodicity', array('class' => 'col-sm-4'))?>
				<div class="field-group<?=$model->hasErrors('donation_periodicity') ? ' error' : '';?>">
						<div class="col-sm-8">
								<?=$form->dropDownList($model, 'donation_periodicity', Socio::getDonationPeriodicities(), array('class' => 'form-control' ), array('options' => array('2'=>array('selected'=>true))));?>
						</div>
				</div>
		</div>

		<div class="form-group row">

				<?=$form->labelEx($model, 'donation_index', array('class' => 'col-sm-4'))?>
				<div
				class="field-group<?=$model->hasErrors('donation_index') ? ' error' : '';?>">
				<div class="col-sm-8">
								<?=$form->radioButtonList ( $model, 'donation_index', Socio::getDonationIndexListData(), array ('template' => '{beginLabel}{input}{labelTitle}{endLabel}','separator' => '','labelOptions' => array ('class' => 'radio-inline' ) ) );;?>
										</div>
			</div>
		</div>

		</div>



	</div>

	<div>
		<p>
			Si prefieres hacernos una transferencia:  BANCO POPULAR <strong>0075-1151-15-0600129425</strong>
		</p>

	</div>

	<h3 class="socio">Datos personales</h3>



	<div class="form-group row">
								<?=$form->labelEx($model, 'type', array('class' => 'col-sm-3 col-md-10')); ?>
								<div class="col-sm-9 col-md-10">
			<div
				class="field-group<?=$model->hasErrors('type') ? ' error' : '';?>">
				<div class="radio-group">
															<?=$form->radioButtonList ( $model, 'type', Socio::getTypeListData (), array ('template' => '{beginLabel}{input}{labelTitle}{endLabel}','separator' => '','labelOptions' => array ('class' => 'radio-inline' ) ) );;?>
									</div>
			</div>
		</div>
	</div>

	<div id="particular"
		<?= $model->type != Socio::TYPE_PHYSIC ? 'style="display: none;"' : ''?>>


		<div class="row">

			<div class="col-sm-6 col-lg-3 form-group">
							<?=$form->labelEx($model, 'firstname')?>
							<div
					class="field-group<?=$model->hasErrors('firstname') ? ' error' : '';?>">
								<?=$form->textField($model, 'firstname', array('class' => 'form-control'))?>
							</div>
			</div>

			<div class="col-sm-6 col-lg-3 form-group">
							<?=$form->labelEx($model, 'lastname')?>
							<div
					class="field-group<?=$model->hasErrors('lastname') ? ' error' : '';?>">
								<?=$form->textField($model, 'lastname', array('class' => 'form-control'))?>
							</div>
			</div>

			<div class="col-sm-6 col-lg-3 form-group">
							<?= $form->labelEx($model, 'sex'); ?>
							<div
					class="field-group<?=$model->hasErrors('sex') ? ' error' : '';?>">
					<div class="radio-group">
								<?= $form->radioButtonList($model, 'sex', Socio::getGenderListData(),array('template' => '{beginLabel}{input}{labelTitle}{endLabel}','separator' => '','labelOptions' => array ('class' => 'radio-inline' ) ) )?>
								</div>
				</div>
			</div>



		</div>



		<div class="row">


			<div class="form-group col-sm6 col-lg-3">
							<?=$form->labelEx($model, 'birthdate')?>
							<div
					class="field-group<?=$model->hasErrors('birthdate') ? ' error' : '';?>">
								<?php $this->widget("zii.widgets.jui.CJuiDatePicker", array('model' => $model, 'attribute' => 'birthdate', 'options' => array(
												'changeMonth' => true,
      											'changeYear' => true,
												'dateFormat' => 'dd/mm/yy'), 'htmlOptions' => array('class' => 'form-control') ));?>
							</div>
			</div>

			<div class="col-sm-6 col-lg-3 form-group">
							<?=$form->labelEx($model, 'id_type')?>
							<div
					class="field-group<?=$model->hasErrors('id') ? ' error' : '';?>">
								<?=$form->dropDownList($model, 'id_type', Socio::getIdTypeDataList(), array('class' => 'form-control'))?>
							</div>
			</div>

			<div class="col-sm-6 col-lg-3 form-group">
							<?=$form->labelEx($model, 'id')?>
							<div
					class="field-group<?=$model->hasErrors('id') ? ' error' : '';?>">
								<?=$form->textField($model, 'id',  array('class' => 'form-control') )?>
							</div>
			</div>





		</div>

	</div>

	<div id="empresa"
		<?= $model->type != Socio::TYPE_JURIDIC ? 'style="display: none;"' : ''?>>

		<div class="row">

			<div class="col-sm-6 col-lg-3 form-group">
								<?=$form->labelEx($model, 'company_name')?>
								<div
					class="field-group<?=$model->hasErrors('company_name') ? ' error' : '';?>">
									<?=$form->textField($model, 'company_name', array('class' => 'form-control'))?>
								</div>
			</div>

			<div class="col-sm-6 col-lg-3 form-group">
								<?=$form->labelEx($model, 'company_id')?>
								<div
					class="field-group<?=$model->hasErrors('company_id') ? ' error' : '';?>">
									<?=$form->textField($model, 'company_id', array('class' => 'form-control'))?>
								</div>

			</div>

			<div class="col-sm-6 col-lg-3 form-group">
								<?=$form->labelEx($model, 'company_contact')?>
								<div
					class="field-group<?=$model->hasErrors('company_contact') ? ' error' : '';?>">
									<?=$form->textField($model, 'company_contact', array('class' => 'form-control'))?>
								</div>

			</div>

		</div>


	</div>

	<div class="row">
		<div class="col-sm-6 col-lg-3 form-group">
								<?=$form->labelEx($model, 'phone')?>
								<div
				class="field-group<?=$model->hasErrors('phone') ? ' error' : '';?>">
									<?=$form->textField($model, 'phone', array('class' => 'form-control'))?>
								</div>
		</div>
		<div class="col-sm-6 col-lg-3 form-group">
								<?= $form->labelEx($model, 'mobile')?>
								<div
				class="field-group<?=$model->hasErrors('mobile') ? ' error' : '';?>">
									<?=$form->textField($model, 'mobile', array('class' => 'form-control'))?>
								</div>
		</div>

		<div class="col-sm-6 col-lg-3 form-group">
								<?= $form->labelEx($model, 'email')?>
								<div
				class="field-group<?=$model->hasErrors('email') ? ' error' : '';?>">
									<?=$form->textField($model, 'email', array('class' => 'form-control'))?>
								</div>
		</div>
	</div>



	<h3 class="socio">Dirección postal</h3>

	<div class="row">

			<div class="row col-lg-10">

				<div class="col-sm-6 col-md-4 form-group">
								<?=$form->labelEx($model, 'address_street')?>
								<div
						class="field-group<?=$model->hasErrors('address_street') ? ' error' : '';?>">
								<?=$form->textField($model, 'address_street', array('class' => 'form-control'))?>
								</div>
				</div>
				<div class="col-sm-3 col-md-2 form-group">
								<?=$form->labelEx($model, 'address_number')?>
								<div
						class="field-group<?=$model->hasErrors('address_number') ? ' error' : '';?>">
									<?=$form->textField($model, 'address_number', array('class' => 'form-control'))?>
								</div>
				</div>


				<div class="col-sm-3 col-md-2 form-group">
								<?=$form->labelEx($model, 'address_other')?>
								<div
						class="field-group<?=$model->hasErrors('address_other') ? ' error' : '';?>">
								<?=$form->textField($model, 'address_other', array('class' => 'form-control'))?>
								</div>
				</div>


				<div class="col-sm-6 col-md-4 form-group">
								<?= $form->labelEx($model, 'address_city'); ?>
								<div
						class="field-group<?=$model->hasErrors('address_city') ? ' error' : '';?>">
								<?= $form->textField($model, 'address_city', array('class' => 'form-control')); ?>
								</div>
				</div>



				<div class="col-sm-6 col-md-4 form-group">
								<?=$form->labelEx($model, 'address_province')?>
								<div
						class="field-group<?=$model->hasErrors('address_province') ? ' error' : '';?>">
								<?= $form->dropDownList($model, 'address_province', Socio::getProvinceListData(), array('class' => 'form-control')); ?>
								</div>
				</div>

				<div class="col-sm-6 col-md-4 form-group">
								<?= $form->labelEx($model, 'address_postal_code'); ?>
								<div
						class="field-group<?=$model->hasErrors('address_postal_code') ? ' error' : '';?>">
									<?= $form->textField($model, 'address_postal_code', array('class' => 'form-control')); ?>
								</div>
				</div>


				<div class="col-sm-6 col-md-4 form-group">
								<?=$form->labelEx($model, 'address_country')?>
								<div
						class="field-group<?=$model->hasErrors('address_country') ? ' error' : '';?>">
									<?= $form->dropDownList($model, 'address_country', Socio::getCountryListData(), array('class' => 'form-control')); ?>
								</div>
				</div>

			</div>

		</div>

	<h3 class="socio"><?=Yii::t('SocioModule.main', 'Bank information')?></h3>

	<div class="row">


		<div class="col-md-4 form-group">
							<?=$form->labelEx($model, 'bank_name')?>
							<div
				class="field-group<?=$model->hasErrors('bank_name') ? ' error' : '';?>">
							<?=$form->textField($model, 'bank_name', array('class' => 'form-control'))?>
							</div>
		</div>
		<div class="col-md-4 form-group">
							<?=$form->labelEx($model, 'account_number')?>
							<div
				class="field-group<?=$model->hasErrors('account_number') ? ' error' : '';?>">
							<?php //$this->widget("CMaskedTextField", array( 'model' => $model, 'attribute' => 'account_number', 'mask' =>  '9999/9999/99/99999999', 'htmlOptions' => array('class' => 'form-control') ) ); ?>
							<?=$form->textField($model, 'account_number', array('class' => 'form-control'))?>
							</div>
		</div>
		<div class="col-md-4 form-group">
							<?=$form->labelEx($model, 'account_owner')?>
							<div
				class="field-group<?=$model->hasErrors('account_owner') ? ' error' : '';?>">
							<?=$form->textField($model, 'account_owner', array('class' => 'form-control'))?>
							</div>
		</div>



	</div>

	<h3 class="socio">Otros datos</h3>

	<div class="row">

		<div class="col-sm-6 col-lg-3 form-group">
						<?=$form->labelEx($model, 'known')?>
						<div
				class="field-group<?=$model->hasErrors('known') ? ' error' : '';?>">
							<?=$form->dropDownList($model, 'known', Socio::getKnownListData(), array('class' => 'form-control'))?>
						</div>
		</div>

		<div class="col-sm-12 form-group">
						<?=$form->labelEx($model, 'comments')?>
						<div
				class="field-group<?=$model->hasErrors('comments') ? ' error' : '';?>">
						<?=$form->textArea($model, 'comments', array('class' => 'form-control'))?>
						</div>
		</div>

	</div>

	<p class="small">
		Al hacerte socia/o colaborador recibirás información periódica sobre la actualidad de OCULARIS, normalmente una Newsletter cada tres meses donde te informaremos de las novedades acerca de nuestros proyectos, actividades y campañas de sensibilización que llevamos a cabo.
	</p>

	<div class="row">



		<div class="col-sm-3 col-lg-2 text-center small">
			<?=CHtml::image(Yii::app()->theme->baseUrl . '/images/litessl_tl_trans.gif', 'PositiveSSL')?><br/>
			<small><?=CHtml::link('Acerca de los certificados SSL', 'https://www.positivessl.com/ssl-certificate-positivessl.php', array('target' => '_blank'))?></small>
		</div>
		<div class="col-sm-9 col-md-10 small">
			<p>Estas navegando bajo una web segura con SSL.</p>

			<p>Los datos personales que nos facilitas serán incorporados a un fichero de datos de Propiedad de OCULARIS Associació en C/Camí del Coll Nº7 de Sitges (Barcelona). Su finalidad es la de gestionar de manera adecuada el régimen de socios/colaboradores y sus donaciones, además de mantenerte informado/a acerca de nuestras actividades. Si deseas ejercitar tu derecho de acceso, rectificación, cancelación u oposición, puedes dirigirte a OCULARIS en la dirección indicada o en <?=CHtml::link('hola@ocularis-jp.org','mailto:hola@ocularis-jp.org')?></p>
		</div>

	</div>

	<div class="buttons">
		<input type="submit"
			value="Hacerme socio"
			class="btn btn-primary btn-lg">
	</div>


<?php $this->endWidget();?>


</div>


<?php

	Yii::app ()->clientScript->registerSCript ( 'socioform', '

		$("#Socio_address_country").on("change", function(){ if($(this).val() != "es") { $("#Socio_address_province").attr("disabled","disabled");}  else { $("#Socio_address_province").removeAttr("disabled");} } );

		$("#Socio_type").on("change",function(){


				if($("input[type=radio]:checked",$(this)).val() == "1" ){

					$("#empresa").show();
					$("#particular").hide();
					$("#particular input").val("");
				}
				 else
				{
					$("#empresa").hide();
					$("#empresa input").val("");
					$("#particular").show();
				}
		}  );

		' );

	?>

<?php endif;?>
