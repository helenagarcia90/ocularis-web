<p>Hola,</p>

<p>Alguien se ha dado de alta como socio.</p>

<?php 
/* @var $model Socio */
$types = Socio::getTypeListData();
$id_types = Socio::getIdTypeDataList();

$provincias = Socio::getProvinceListData();
$paises = Socio::getCountryListData(); 

$period = Socio::getDonationPeriodicities();

$known = Socio::getKnownListData();
?>
<h2>Datos socio:</h2>

<?php if($model->type == Socio::TYPE_PHYSIC):?>

Sexo: <?=$model->sex;?><br/>
Nombre: <?=$model->firstname;?><br/>
Apellidos: <?=$model->lastname;?><br/>
Tipo de documento: <?= $id_types[$model->id_type]?><br/>
Documento: <?= $model->id?><br/>
Nacimiento: <?= $model->birthdate?><br/>

<?php
elseif($model->type == Socio::TYPE_JURIDIC):?>

Empresa: <?=$model->company_name;?><br/>
CIF: <?=$model->company_id;?><br/>
Persona de contacto: <?=$model->company_contact;?><br/>
<?php endif;?>

Teléfono: <?=$model->phone;?><br/>
Móvil: <?=$model->mobile;?><br/>
Email: <?=$model->email;?><br/><br/>

<h2>Dirección</h2>

Dirección: <?=$model->address_street?>, <?=$model->address_number?><br/>
<?=$model->address_other?><br/>
<?=$model->address_postal_code?> <?=$model->address_city?>, <?=$model->address_province !== null ? $provincias[$model->address_province] : ''?><br/>
<?=$paises[$model->address_country]?><br/>


<h2>Datos Bancarios:</h2>
Donación: <?= $model->donation?> €<br/>
Periodicidad: <?= $period[$model->donation_periodicity]?><br/>
Actualización anual IPC: <?= $model->donation_index ? 'Sí' : 'No' ?><br/>
Nombre entidad: <?= $model->bank_name?><br/>
Titual de la cuenta: <?= $model->account_owner?><br/>
Numero de cuenta: <?= $model->account_number?><br/><br/>

<h2>Otros datos</h2>
Cómo conocí a Ocularis: <?= $model->known == 5 ? $model->known_other : $known[$model->known]?><br/>
Comentarios:<br/><?= $model->comments?><br/>


<br/><br/>
<p>Gracias,</p>