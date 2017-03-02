<?php

/**
 * This is the model class for table "socio".
 *
 * The followings are the available columns in table 'socio':
 * @property integer $id_socio
 * @property integer $type
 * @property integer $sex
 * @property string $firstname
 * @property string $lastname
 * @property integer $id_type
 * @property string $id
 * @property string $birthdate
 * @property string $address_street
 * @property string $address_number
 * @property string $address_other
 * @property string $address_country
 * @property integer $address_province
 * @property string $address_city
 * @property integer $address_postal_code
 * @property string $phone
 * @property string $mobile
 * @property integer $known
 * @property string $known_other
 * @property string $comments
 * @property string $donation
 * @property integer $donation_index
 * @property integer $donation_periodicity
 * @property string $account_number
 * @property integer $account_owner
 * @property string $email
 *
 * @property string $company_name
 * @property string $company_id
 * @property string $company_contact

 */
class Socio extends CActiveRecord
{

	const GENDER_M = 'M';
	const GENDER_F = 'F';

	const TYPE_JURIDIC = 1;
	const TYPE_PHYSIC = 2;

	const ID_TYPE_DNI = 1;
	const ID_TYPE_NIE = 2;
	const ID_TYPE_PASSPORT = 3;
	const ID_TYPE_CIF = 4;

	public $address_country = 'es';
	public $donation_index = 0;
	public $type = self::TYPE_PHYSIC;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'socio';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{

		return array(

			array('address_street, address_number, address_country, address_province, address_city, address_postal_code, donation, donation_index, donation_periodicity, bank_name, account_number, account_owner, email', 'required'),

			array('type, sex, firstname, lastname, id_type, id, birthdate', 'required', 'on' => 'physic, default'),
			array('company_name, company_contact, company_id', 'required', 'on' => 'juridic, default'),

			array('type, id_type, address_province, address_postal_code, known, donation_periodicity', 'numerical', 'integerOnly'=>true),
			array('firstname, lastname', 'length', 'max'=>50),
			array('id, address_city, known_other', 'length', 'max'=>32),
			array('address_street, address_other', 'length', 'max'=>100),
			array('address_number', 'length', 'max'=>16),
			array('address_country', 'length', 'max'=>4),
			array('phone, mobile', 'length', 'max'=>20),
			array('donation', 'length', 'max'=>10),
			array('email', 'email'),
			array('comments', 'safe'),
			array('company_id, company_contact, company_name', 'safe'),

		);
	}

	public function beforeSave()
	{

		$locale = Yii::app()->locale;
		$dateTimePattern = $locale->getDateTimeFormat();

		$format = $locale->getDateFormat('medium');

		$this->birthdate = CTimestamp::formatDate('Y-m-d', CDateTimeParser::parse($this->birthdate, $format));

		return parent::beforeSave();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(

				'id_socio' => "ID",
				'type' => Yii::t('Socio.main','Tipo de donante'),
				'sex' => Yii::t('Socio.main','Sexo'),
				'firstname' => Yii::t('Socio.main','Nombre'),
				'lastname' => Yii::t('Socio.main','Apellidos'),
				'id_type' => Yii::t('Socio.main','Tipo de documento'),
				'id' => Yii::t('Socio.main','Número de documento'),
				'birthdate' => Yii::t('Socio.main','Fecha de nacimiento'),
				'address_street' => Yii::t('Socio.main','Dirección (vía)'),
				'address_number' => Yii::t('Socio.main','Número'),
				'address_other' => Yii::t('Socio.main','Otros Datos'),
				'address_country' => Yii::t('Socio.main','País'),
				'address_province' => Yii::t('Socio.main','Provincia'),
				'address_city' => Yii::t('Socio.main','Población'),
				'address_postal_code' => Yii::t('Socio.main','Código Postal'),
				'phone' => Yii::t('Socio.main','Teléfono'),
				'mobile' => Yii::t('Socio.main','Móvil'),
				'known' => Yii::t('Socio.main','Como nos conociste'),
				'known_other' => Yii::t('Socio.main','Otro'),
				'comments' => Yii::t('Socio.main','Comentarios'),
				'donation' => Yii::t('Socio.main','Quiero donar'),
				'donation_index' => Yii::t('Socio.main','Actualización anual de tu cuota'),
				'donation_periodicity' => Yii::t('Socio.main','¿Cada cuánto?'),
				'bank_name' => Yii::t('Socio.main','Nombre de la entidad'),
				'account_number' => Yii::t('Socio.main','Número de cuenta'),
				'account_owner' => Yii::t('Socio.main','Titular'),
				'email' => Yii::t('Socio.main','Email'),
				'company_sex' => Yii::t('Socio.main','Sexo'),
				'company_id' => Yii::t('Socio.main','CIF'),
				'company_name' => Yii::t('Socio.main','Nombre de la empresa'),
				'company_contact' => Yii::t('Socio.main','Persona de contacto'),

		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id_socio',$this->id_socio);
		$criteria->compare('type',$this->type);
		$criteria->compare('sex',$this->sex);
		$criteria->compare('firstname',$this->firstname,true);
		$criteria->compare('lastname',$this->lastname,true);
		$criteria->compare('id_type',$this->id_type);
		$criteria->compare('id',$this->id,true);
		$criteria->compare('birthdate',$this->birthdate,true);
		$criteria->compare('address_street',$this->address_street,true);
		$criteria->compare('address_number',$this->address_number,true);
		$criteria->compare('address_other',$this->address_other,true);
		$criteria->compare('address_country',$this->address_country,true);
		$criteria->compare('address_province',$this->address_province);
		$criteria->compare('address_city',$this->address_city,true);
		$criteria->compare('address_postal_code',$this->address_postal_code);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('kown',$this->kown);
		$criteria->compare('known_other',$this->known_other,true);
		$criteria->compare('comments',$this->comments,true);
		$criteria->compare('donation',$this->donation,true);
		$criteria->compare('donation_periodicity',$this->donation_periodicity);
		$criteria->compare('account_number',$this->account_number,true);
		$criteria->compare('account_owner',$this->account_owner);
		$criteria->compare('email',$this->email,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Socio the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public static function getGenderListData()
	{
		return array(
			self::GENDER_M => Yii::t('SocioModule.main', 'Hombre'),
			self::GENDER_F => Yii::t('SocioModule.main', 'Mujer'),
		);
	}

	public static function getTypeListData()
	{
		return array(
				self::TYPE_PHYSIC => Yii::t('SocioModule.main', 'Particular'),
				self::TYPE_JURIDIC => Yii::t('SocioModule.main', 'Empresa'),
		);
	}

	public static function getIdTypeDataList()
	{
		return array(
				self::ID_TYPE_DNI => Yii::t('SocioModule.main', 'DNI'),
				self::ID_TYPE_NIE => Yii::t('SocioModule.main', 'NIE'),
				self::ID_TYPE_PASSPORT => Yii::t('SocioModule.main', 'Passport'),
				//self::ID_TYPE_CIF => Yii::t('SocioModule.main', 'CIF'),
		);
	}

	public static function getCountryListData()
	{

		return array(

				'af' => 'Afghanistan',
				'ax' => 'Åland Islands',
				'al' => 'Albania',
				'dz' => 'Algeria',
				'as' => 'American Samoa',
				'ad' => 'Andorra',
				'ao' => 'Angola',
				'ai' => 'Anguilla',
				'aq' => 'Antarctica',
				'ag' => 'Antigua and Barbuda',
				'ar' => 'Argentina',
				'am' => 'Armenia',
				'aw' => 'Aruba',
				'au' => 'Australia',
				'at' => 'Austria',
				'az' => 'Azerbaijan',
				'bs' => 'Bahamas',
				'bh' => 'Bahrain',
				'bd' => 'Bangladesh',
				'bb' => 'Barbados',
				'by' => 'Belarus',
				'be' => 'Belgium',
				'bz' => 'Belize',
				'bj' => 'Benin',
				'bm' => 'Bermuda',
				'bt' => 'Bhutan',
				'bo' => 'Bolivia',
				'ba' => 'Bosnia and Herzegovina',
				'bw' => 'Botswana',
				'bv' => 'Bouvet Island',
				'br' => 'Brazil',
				'io' => 'British Indian Ocean Territory',
				'bn' => 'Brunei Darussalam',
				'bg' => 'Bulgaria',
				'bf' => 'Burkina Faso',
				'bi' => 'Burundi',
				'kh' => 'Cambodia',
				'cm' => 'Cameroon',
				'ca' => 'Canada',
				'cv' => 'Cape Verde',
				'ky' => 'Cayman Islands',
				'cf' => 'Central African Republic',
				'td' => 'Chad',
				'cl' => 'Chile',
				'cn' => 'China',
				'cx' => 'Christmas Island',
				'cc' => 'Cocos (Keeling) Islands',
				'co' => 'Colombia',
				'km' => 'Comoros',
				'cg' => 'Congo',
				'cd' => 'Congo, The Democratic Republic of The',
				'ck' => 'Cook Islands',
				'cr' => 'Costa Rica',
				'ci' => 'Cote D\'ivoire',
				'hr' => 'Croatia',
				'cu' => 'Cuba',
				'cy' => 'Cyprus',
				'cz' => 'Czech Republic',
				'dk' => 'Denmark',
				'dj' => 'Djibouti',
				'dm' => 'Dominica',
				'do' => 'Dominican Republic',
				'ec' => 'Ecuador',
				'eg' => 'Egypt',
				'sv' => 'El Salvador',
				'gq' => 'Equatorial Guinea',
				'er' => 'Eritrea',
				'ee' => 'Estonia',
				'et' => 'Ethiopia',
				'fk' => 'Falkland Islands (Malvinas)',
				'fo' => 'Faroe Islands',
				'fj' => 'Fiji',
				'fi' => 'Finland',
				'fr' => 'France',
				'gf' => 'French Guiana',
				'pf' => 'French Polynesia',
				'tf' => 'French Southern Territories',
				'ga' => 'Gabon',
				'gm' => 'Gambia',
				'ge' => 'Georgia',
				'de' => 'Germany',
				'gh' => 'Ghana',
				'gi' => 'Gibraltar',
				'gr' => 'Greece',
				'gl' => 'Greenland',
				'gd' => 'Grenada',
				'gp' => 'Guadeloupe',
				'gu' => 'Guam',
				'gt' => 'Guatemala',
				'gg' => 'Guernsey',
				'gn' => 'Guinea',
				'gw' => 'Guinea-bissau',
				'gy' => 'Guyana',
				'ht' => 'Haiti',
				'hm' => 'Heard Island and Mcdonald Islands',
				'va' => 'Holy See (Vatican City State)',
				'hn' => 'Honduras',
				'hk' => 'Hong Kong',
				'hu' => 'Hungary',
				'is' => 'Iceland',
				'in' => 'India',
				'id' => 'Indonesia',
				'ir' => 'Iran, Islamic Republic of',
				'iq' => 'Iraq',
				'ie' => 'Ireland',
				'im' => 'Isle of Man',
				'il' => 'Israel',
				'it' => 'Italy',
				'jm' => 'Jamaica',
				'jp' => 'Japan',
				'je' => 'Jersey',
				'jo' => 'Jordan',
				'kz' => 'Kazakhstan',
				'ke' => 'Kenya',
				'ki' => 'Kiribati',
				'kp' => 'Korea, Democratic People\'s Republic of',
				'kr' => 'Korea, Republic of',
				'kw' => 'Kuwait',
				'kg' => 'Kyrgyzstan',
				'la' => 'Lao People\'s Democratic Republic',
				'lv' => 'Latvia',
				'lb' => 'Lebanon',
				'ls' => 'Lesotho',
				'lr' => 'Liberia',
				'ly' => 'Libyan Arab Jamahiriya',
				'li' => 'Liechtenstein',
				'lt' => 'Lithuania',
				'lu' => 'Luxembourg',
				'mo' => 'Macao',
				'mk' => 'Macedonia, The Former Yugoslav Republic of',
				'mg' => 'Madagascar',
				'mw' => 'Malawi',
				'my' => 'Malaysia',
				'mv' => 'Maldives',
				'ml' => 'Mali',
				'mt' => 'Malta',
				'mh' => 'Marshall Islands',
				'mq' => 'Martinique',
				'mr' => 'Mauritania',
				'mu' => 'Mauritius',
				'yt' => 'Mayotte',
				'mx' => 'Mexico',
				'fm' => 'Micronesia, Federated States of',
				'md' => 'Moldova, Republic of',
				'mc' => 'Monaco',
				'mn' => 'Mongolia',
				'me' => 'Montenegro',
				'ms' => 'Montserrat',
				'ma' => 'Morocco',
				'mz' => 'Mozambique',
				'mm' => 'Myanmar',
				'na' => 'Namibia',
				'nr' => 'Nauru',
				'np' => 'Nepal',
				'nl' => 'Netherlands',
				'an' => 'Netherlands Antilles',
				'nc' => 'New Caledonia',
				'nz' => 'New Zealand',
				'ni' => 'Nicaragua',
				'ne' => 'Niger',
				'ng' => 'Nigeria',
				'nu' => 'Niue',
				'nf' => 'Norfolk Island',
				'mp' => 'Northern Mariana Islands',
				'no' => 'Norway',
				'om' => 'Oman',
				'pk' => 'Pakistan',
				'pw' => 'Palau',
				'ps' => 'Palestinian Territory, Occupied',
				'pa' => 'Panama',
				'pg' => 'Papua New Guinea',
				'py' => 'Paraguay',
				'pe' => 'Peru',
				'ph' => 'Philippines',
				'pn' => 'Pitcairn',
				'pl' => 'Poland',
				'pt' => 'Portugal',
				'pr' => 'Puerto Rico',
				'qa' => 'Qatar',
				're' => 'Reunion',
				'ro' => 'Romania',
				'ru' => 'Russian Federation',
				'rw' => 'Rwanda',
				'sh' => 'Saint Helena',
				'kn' => 'Saint Kitts and Nevis',
				'lc' => 'Saint Lucia',
				'pm' => 'Saint Pierre and Miquelon',
				'vc' => 'Saint Vincent and The Grenadines',
				'ws' => 'Samoa',
				'sm' => 'San Marino',
				'st' => 'Sao Tome and Principe',
				'sa' => 'Saudi Arabia',
				'sn' => 'Senegal',
				'rs' => 'Serbia',
				'sc' => 'Seychelles',
				'sl' => 'Sierra Leone',
				'sg' => 'Singapore',
				'sk' => 'Slovakia',
				'si' => 'Slovenia',
				'sb' => 'Solomon Islands',
				'so' => 'Somalia',
				'za' => 'South Africa',
				'gs' => 'South Georgia and The South Sandwich Islands',
				'es' => 'Spain',
				'lk' => 'Sri Lanka',
				'sd' => 'Sudan',
				'sr' => 'Suriname',
				'sj' => 'Svalbard and Jan Mayen',
				'sz' => 'Swaziland',
				'se' => 'Sweden',
				'ch' => 'Switzerland',
				'sy' => 'Syrian Arab Republic',
				'tw' => 'Taiwan, Province of China',
				'tj' => 'Tajikistan',
				'tz' => 'Tanzania, United Republic of',
				'th' => 'Thailand',
				'tl' => 'Timor-leste',
				'tg' => 'Togo',
				'tk' => 'Tokelau',
				'to' => 'Tonga',
				'tt' => 'Trinidad and Tobago',
				'tn' => 'Tunisia',
				'tr' => 'Turkey',
				'tm' => 'Turkmenistan',
				'tc' => 'Turks and Caicos Islands',
				'tv' => 'Tuvalu',
				'ug' => 'Uganda',
				'ua' => 'Ukraine',
				'ae' => 'United Arab Emirates',
				'gb' => 'United Kingdom',
				'us' => 'United States',
				'um' => 'United States Minor Outlying Islands',
				'uy' => 'Uruguay',
				'uz' => 'Uzbekistan',
				'vu' => 'Vanuatu',
				've' => 'Venezuela',
				'vn' => 'Viet Nam',
				'vg' => 'Virgin Islands, British',
				'vi' => 'Virgin Islands, U.S.',
				'wf' => 'Wallis and Futuna',
				'eh' => 'Western Sahara',
				'ye' => 'Yemen',
				'zm' => 'Zambia',
				'zw' => 'Zimbabwe',

		);

	}

	public static function getProvinceListData()
	{

		return array(
				'52' => 'A Coruña',
				'51' => 'Àlava',
				'22' => 'Albacete',
				'23' => 'Alicante',
				'24' => 'Almería',
				'3' => 'Asturias',
				'25' => 'Ávila',
				'26' => 'Badajoz',
				'27' => 'Baleares',
				'28' => 'Barcelona',
				'29' => 'Burgos',
				'30' => 'Cáceres',
				'31' => 'Cádiz',
				'9' => 'Cantabria',
				'32' => 'Castellón',
				'21' => 'Ceuta',
				'33' => 'Ciudad Real',
				'34' => 'Córdoba',
				'36' => 'Cuenca',
				'37' => 'Girona',
				'38' => 'Granada',
				'39' => 'Guadalajara',
				'40' => 'Guipúzcoa',
				'41' => 'Huelva',
				'42' => 'Huesca',
				'43' => 'Jaén',
				'46' => 'La Rioja',
				'5' => 'Las Palmas',
				'44' => 'León',
				'45' => 'Lleida',
				'47' => 'Lugo',
				'48' => 'Madrid',
				'49' => 'Málaga',
				'50' => 'Melilla',
				'0' => 'Murcia',
				'1' => 'Navarra',
				'2' => 'Orense',
				'4' => 'Palencia',
				'6' => 'Pontevedra',
				'7' => 'Salamanca',
				'8' => 'Santa Cruz de Teneri',
				'10' => 'Segovia',
				'11' => 'Sevilla',
				'12' => 'Soria',
				'13' => 'Tarragona',
				'14' => 'Teruel',
				'15' => 'Toledo',
				'16' => 'Valencia',
				'17' => 'Valladolid',
				'18' => 'Vizcaya',
				'19' => 'Zamora',
				'20' => 'Zaragoza'

		);

	}

	public static function getKnownListData()
	{
		return array(

				1 => Yii::t('SocioModule.main', 'Internet', 'es'),
				2 => Yii::t('SocioModule.main', 'En uno de vuestros eventos', 'es'),
				3 => Yii::t('SocioModule.main', 'Prensa', 'es'),
				4 => Yii::t('SocioModule.main', 'De un socio nuestro', 'es'),
				5 => Yii::t('SocioModule.main', 'Otro', 'es'),

		);
	}

	public static function getDonationPeriodicities()
	{
		return array(

				//'' => Yii::t('SocioModule.main', 'Selecciona'),
				2 => Yii::t('SocioModule.main', 'Semestral'),
				1 => Yii::t('SocioModule.main', 'Anual'),
				//4 => Yii::t('SocioModule.main', 'Trimestral'),
				//5 => Yii::t('SocioModule.main', 'Mensual'),
				3 => Yii::t('SocioModule.main', 'Puntual'),

		);
	}


	public static function getDonationIndexListData()
	{
		return array(
			1 => 'IPC',
			0 => 'No',
		);
	}
}
