<?php $this->layout = '//layouts/main';

if(Yii::app()->themeConfig->get('home-meta-title') != '')
	$this->pageTitle = Yii::app()->themeConfig->get('home-meta-title');
else
	$this->pageTitle = Yii::app()->name;

if(Yii::app()->themeConfig->get('home-meta-description') != '')
	Yii::app()->clientScript->registerMetaTag(Yii::app()->themeConfig->get('home-meta-description'), 'description');

if(Yii::app()->themeConfig->get('home-meta-keywords') != '')
	Yii::app()->clientScript->registerMetaTag(Yii::app()->themeConfig->get('home-meta-keywords'), 'keywords');

if(Yii::app()->themeConfig->get('home-cover-2') != '')
{
	Yii::app()->clientScript->registerCss('background1', '

		#teaser
		{
			background-image: url(\''. Yii::app()->imageCache->createUrl('home-cover',Yii::app()->themeConfig->get('home-cover-2')).'\');

		}

');
}

if(Yii::app()->themeConfig->get('home-cover') != '')
{

	Yii::app()->clientScript->registerCss('background2', '

			.features
			{
				background-repeat: no-repeat;
				background-position: center center;
				background-attachment: fixed;
				background-size: cover;
				background-image: url(\''. Yii::app()->imageCache->createUrl('home-cover',Yii::app()->themeConfig->get('home-cover')).'\') !important;
			}

			#main .features h3,
			#main .features p
			{
				color: #fff !important;
			}

		');

}

?>

<div id="main">

    <div class="lift text-center" id="ventajas">

        <div class="container">

            <div class="row">

                <div class="col-lg-4">

                    <img src="<?=Yii::app()->theme->baseUrl."/"; ?>images/teaming.png" class="center-block" width="70%"/>

                    <h2>DONACIONES LOWCOST</h2>

                    <p>GRACIAS A LA PLATAFORMA TEAMING TÚ PUEDES<br />
                        COLABORAR CON NUESTRA CAUSA POR TAN SÓLO</p>

                    <p><strong class="green">1€ AL MES</strong>:)</p>

                    <p><a class="btn btn-default bottom-aligned-text green" href="#" role="button">LEER MÁS</a></p>

                </div>

                <div class="col-lg-4">

                    <img src="<?=Yii::app()->theme->baseUrl."/"; ?>images/voluntario.png" class="center-block"  width="70%"/><!-- /* H  */-->

                    <h2>¿QUIERES SER VOLUNTARIO?</h2>

                    <p>OFTALMÓLOGO, ÓPTICO, COMUNICACIÓN, MKT,<br />
                        EMPRESARIO... TODOS SUMAMOS!!!<br />
                        CONTACTA CON NOSOTROS :)</p>

                    <p><a class="btn btn-default bottom-aligned-text green" href="#" role="button">LEER MÁS</a></p>

                </div>

                <div class="col-lg-4">

                    <img src="<?=Yii::app()->theme->baseUrl."/"; ?>images/hucha.png"  class="center-block" width="70%"/>

                    <h2>APADRINA UNA HUCHA</h2>

                    <p>¿TRABAJAS EN UN ESTABLECIMIENTO CARA<br />
                        AL PÚBLICO? TE HACEMOS LLEGAR UNA HUCHA, DISPLAY Y NUESTROS DÍPTICOS INFORMATIVOS.<br />
                        CONTACTA CON NOSOTROS Y VEMOS :)</p>

                    <p><a class="btn btn-default bottom-aligned-text green" href="#" role="button">LEER MÁS</a></p>

                </div>

            </div>

        </div>

    </div>

    <div class="no-lift" id="conocenos">

        <div class="container">

            <h2 class="left-mark">CONÓCENOS</h2>

            <img src="<?=Yii::app()->theme->baseUrl."/"; ?>images/logoFondo.png" class="pull-left">

            <p><strong>OCULARIS</strong> es una entidad sin ánimo de lucro que centra su actividad en la formación oftalmológica y óptica en países en vías de desarrollo del África Subsahariana. Formamos a profesionales de la salud visual en sus países de origen, con sus propias herramientas y sus propias enfermedades. Trabajamos y luchamos para conseguir un acceso universal e igualitario a la salud visual, mejorar la calidad de vida de las personas y colaborar con la erradicación de la pobreza.</p>

            <p>Fue fundada altruistamente por 16 personas en 2010 y está compuesta mayoritariamente por médicos oftalmólogos y ópticos-optometristas. Además del personal sanitario que se desplaza a formar, contamos con la valiosísima ayuda de una red de voluntarios que colaboran en la captación de fondos, contabilidad, comunicación, marketing, ... y de un valioso grupo de soci@s donantes, sin todos ellos, el trabajo que llevamos a cabo sería imposible de realizar. <strong>¿Y tí, te apetece ayudarnos?</strong> :)</p>

            <p class="lead text-center"><strong>“LA FORMACIÓN ES LA CLAVE PARA EL DESARROLLO DE UN PAÍS, MIENTRAS QUE LAS ACCIONES<br />PUNTUALES NO DEJAN NINGÚN LEGADO Y CONDICIONAN SU AUTOSUFICIENCIA”</strong></p>
        </div>
    </div>

    <div class="lift" id="miembros">

        <div class="container">

            <h2 class="left-mark">ORGANIZACIÓN INTERNA</h2>

            <p>Como asociación que somos, nos regimos por las decisiones que se toman asambleariamente en la Junta general de socios. El órgano ejecutivo de la entidad es la Junta Directiva, y los representantes que la forman son elegidos democráticamente. El actual mandato se inició en 2013 y expirará como máximo el 14/06/2016, ya que en nuestros estatutos se detalla que este órgano deberá renovarse cada 3 años máximo. La actual Junta Directiva está compuesta por:</p>

            <div class="row  text-center">

                <div class="col-lg-4">

                    <img src="<?=Yii::app()->theme->baseUrl."/"; ?>images/miembro.png">

                    <h3>Dr. Joan Prat Bartomeu</h3>
                    <p>PRESIDENTE</p>

                </div>
                <div class="col-lg-4">

                    <img src="<?=Yii::app()->theme->baseUrl."/"; ?>images/miembro.png">

                    <h3>Sra. Eulalia Sanchez Herrero</h3>
                    <p>VICEPRESIDENTE</p>

                </div>
                <div class="col-lg-4">

                    <img src="<?=Yii::app()->theme->baseUrl."/"; ?>images/miembro.png">

                    <h3>Sra. Elena Dasca Panadés</h3>
                    <p>SECRETARIA</p>

                </div>

            </div>

            <div class="row  text-center">

                <div class="col-lg-4">

                    <img src="<?=Yii::app()->theme->baseUrl."/"; ?>images/miembro.png">

                    <h3>Sr. Josep Roijals Carrique</h3>
                    <p>TESORERO</p>

                </div>
                <div class="col-lg-4">

                    <img src="<?=Yii::app()->theme->baseUrl."/"; ?>images/miembro.png">

                    <h3>Dr. Jesús Torres Blanch</h3>
                    <p>VOCAL 1</p>

                </div>
                <div class="col-lg-4">

                    <img src="<?=Yii::app()->theme->baseUrl."/"; ?>images/miembro.png">

                    <h3>Sr. Javier Laiseca Perez</h3>
                    <p>VOCAL 2</p>

                </div>

            </div>

            <p class="text-center"><small>
                    OCULARIS Associació con NIF  G-65335861 tiene su domicilio ﬁscal en la Calle Camí del Coll Nº7, 2º2ª de Sitges 08870 (Barcelona), y está inscrita en:
                </small></p>
            <p class="text-center"><small class="text-center">
                    Registre d’Associacions de la Generalitat con el Nº42.702 el 08 de junio de 2010.<br />
                    Registre d’Entitats No Governamentals per al Desenvolupament de la Generalitat de Catalunya con Nº567 el 27 de julio de 2012.<br />
                    Registre Municipal d’Entitats de l’Ajuntament de Sitges con Nº102 el 05 de octubre de 2012.r
                </small></p>

        </div>
    </div>

    <div class="no-lift" id="queHacemos">

        <div class="container">
            <h2 class="left-mark">¿QUÉ HACEMOS?</h2>

            <div class="row">

                <div class="col-lg-6">
                    <h4 class="green">MISIÓN</h4>
                    <p class="text-justify">Formar a médicos oftalmólogos y a optometristas para que puedan llegar ser los futuros profesores de especialidades básicas que hoy en día no existen en su país, como la pediatría, retina, glaucoma y la baja visión. Contribuir y potenciar sus conocimientos y técnicas para que puedan asistir correctamente a un mayor número de personas. Formarles en su propio medio, adaptándonos a sus necesidades y utilizando sus propios recursos, aunque estos sean más arcaicos de los que usamos en los paises occidentales. Transformar la realidad sanitaria y social en la zona más desfavorecida y menos desarrollada del planeta, el África Subsahariana, donde las deficiencias visuales sentencian el futuro de millones de personas sin recursos.</p>
                </div>
                <div class="col-lg-6">
                    <blockquote class="green text-center">
                        <p>
                            "LA FORMACIÓN Y EL CONOCIMIENTO SON IMPRESCINDIBLES PARA EL DESARROLLO DE UN PAÍS Y LA PREVENCIÓN PRECOZ DE ENFERMEDADES VISUALES EVITABLES <br />ES CLAVE"
                        </p>
                    </blockquote>
                </div>

            </div>

            <div class="row">

                <div class="col-lg-6">
                    <blockquote class="green text-center">
                        <p>“SI ME DAS UN PEZ COMERÉ HOY, SI ME <br /> ENSEÑAS A PESCAR PODRÉ COMER CADA <br /> DÍA”</p>
                    </blockquote>
                </div>

                <div class="col-lg-6">
                    <h4 class="green">OBJETIVO (MISIÓN)</h4>
                    <p class="text-justify">Ser un pilar en la formación oftalmológica en países subsaharianos en vías de desarrollo, contando con la confianza e implicación de sus gobiernos, facultativos y profesionales de la salud visual. Nuestra máxima aspiración es erradicar la ceguera evitable y/o curable, disponer de un acceso universal e igualitario a la salud visual, colaborar con la eliminación de la pobreza y mejorar la calidad de vida de la población.</p>
                </div>

            </div>

            <h3>BASÁNDONOS EN:</h3>
            <div class="col-lg-2 valores text-center">
                va<br />lo<br/>res
            </div>
            <div class="col-lg-10">
                <blockquote class="green text-center">
                    <h4>IMPLICACIÓN</h4>
                    <p><small>Con esfuerzo y empatía, impregnamos de profesionalidad a los futuros oftalmólogos, optometristas y enfermeros.</small></p>
                    <h4>PERSEVERANCIA</h4>
                    <p><small>Con compromiso y determinación nos esforzamos continuamente para mejorar y conseguir nuestros objetivos.</small></p>
                    <h4>IGUALDAD</h4>
                    <p><small>Equiparando el conocimiento optométrico y oftalmológico entre Europa y África.</small></p>
                    <h4>HONESTIDAD</h4>
                    <p><small>Trabajamos de forma transversal, comunicamos de forma clara y actuamos decentemente.</small></p>
                    <h4>FORMACIÓN</h4>
                    <p><small>Compartimos el conocimiento médico europeo de forma práctica, considerando la realidad y medios disponibles de cada país. <br />
                            Guiamos y enseñamos a profesionales médicos africanos con el objetivo de mejorar la asistencia en salud ocular de la población.</small></p>

                </blockquote>
            </div>
            <p class="lead text-center"><strong>"LES FORMAMOS EN SU PROPIO MEDIO, ADAPTÁNDONOS A SUS NECESIDADES Y UTILIZANDO<br />
                    SUS PROPIOS RECURSOS, AUNQUE ESTOS SEAN MÁS ARCAICOS QUE LOS QUE USAMOS AQUÍ”</strong></p>
        </div>

    </div>

    <div class="lift">

        <div class="container">
            <h2 class="left-mark">
                ¿POR QUÉ LO HACEMOS?
            </h2>

            <div class="row black-line">

                <div class="col-lg-4 text-center left-black-line">
                    <img src="<?=Yii::app()->theme->baseUrl."/"; ?>images/planeta.png" class="center-block">
                    <h2>POBLACIÓN MUNDIAL <br /><strong>6.697M</strong></h2>
                </div>
                <div class="col-lg-8">

                    <div class="boxgreen1 text-center">285M SUFREN DISCAPACIDAD VISUAL</div>
                    <div class="row boxes">
                        <div class="col-lg-7 boxgreen2 text-center">
                            246 M (86%) BAJA VISIÓN
                        </div>
                        <div class="col-lg-5 boxgreen3 text-center">
                            39 M (14%) CIEGA
                        </div>
                    </div>

                    <div class="row boxes">
                        <div class="col-lg-6 text-center">
                            <p><strong style="
                                    font-size: 150px;
                                    line-height: 110px;
                                ">80%</strong></p>
                            <p style="
                                    font-size: 30px;
                                    font-weight: bold;
                                    line-height: 30px;
                                ">SE PUEDEN CURAR O EVITAR</p>
                        </div>
                        <div class="col-lg-6 text-center">
                            <p><strong style="
                                    font-size: 150px;
                                    line-height: 110px;
                                ">90%</strong></p>
                            <p style="
                                    font-size: 20px;
                                    font-weight: bold;
                                    line-height: 18px;
                                ">NO PUEDEN AFRONTAR SUS ENFERMEDADES POR FALTA DE MEDIOS</p>
                        </div>
                    </div>

                </div>

            </div>

            <p class="lead text-center" STYLE="margin-top: 20px; font-size: 30px; font-weight: bold">SEGÚN<br />
                <img src="<?=Yii::app()->theme->baseUrl."/"; ?>images/logoOMS.png"><br />
                SI NO SE ACTÚA EN ESTE ÁMBITO</p>

            <img src="<?=Yii::app()->theme->baseUrl."/"; ?>images/graficoCiegos.png" class="center-block">

        </div>

    </div>

    <div class="no-lift">

        <img src="<?=Yii::app()->theme->baseUrl."/"; ?>images/saludVisualPobreza.png" class="center-block">

        <div class="container lift text-center">

            <p>La lucha contra la ceguera evitable es, junto con la vacunación, la acción sanitaria más eficaz y rentable en la lucha contra la erradicación de la pobreza en los países en vías de desarrollo, posiblemente ningún otro campo médico contribuya tanto al progreso de estas sociedades. Recuperar la visión supone una nueva oportunidad de poder volver a trabajar y alimentar a la familia, de formarse y, por desgracia, en muchos casos simplemente de sobrevivir.</p>

            <p>La ceguera constituye un factor importante en la disminución de la productividad en los países en vías de desarrollo, a la gran mayoría de las personas que la padecen, les condena a la mendicidad y a la exclusión social. La pobreza es por tanto, un determinante de la ceguera y, a su vez, la ceguera acentúa la pobreza.</p>

            <p>Probablemente sea por este motivo por el que cada vez haya más profesionales en este campo médico que se interesen en la cooperación para el desarrollo. Con tu ayuda esperamos seguir creciendo, haciéndonos ver, para que sociedad e instituciones públicas abran sus ojos y colaboren en esta dirección hasta lograr erradicar la ceguera evitable/prevenible y conseguir un acceso universal e igualitario a la atención de la salud visual.</p>



            <h2 class="green">¡CONTAMOS CONTIGO!</h2>
        </div>

        <img src="<?=Yii::app()->theme->baseUrl."/"; ?>images/mapamundi.png" class="center-block">
        <img src="<?=Yii::app()->theme->baseUrl."/"; ?>images/logoOMS.png" class="center-block">
        <div class="container">
            <p class="text-center"><small>The boundaries and names shown and the designationes used on this map do not imply the expression of any opinion whatsoever on the part of the world health organization concerning the legal status of any country, terriotiry, city or area or of its authorities or concerning the delimitation of it frontiers or boundaries. dotted lines on maps represent approximate border lines for which there may not yet be full agreement.</small></p>
        </div>

        <div class="container">
            <blockquote class="green">
                <p>"La formación y el conocimiento son imprescinbles para el desarrollo de un país
                    y la prevención precoz de enfermedades visuales evitables es clave"</p>
            </blockquote>
        </div>

        <div class="container">

            <h3>la ceguera infantil</h3>

            <div class="row">
                <div class="col-lg-5">
                    <img src="<?=Yii::app()->theme->baseUrl."/"; ?>images/flecha.png">
                </div>
                <div class="col-lg-7 lift">
                    <div class="row">
                        <div class="col-lg-5 text-center">
                            <img src="<?=Yii::app()->theme->baseUrl."/"; ?>images/60Percent.png">
                            <p>hasta un 60% de la población infantil muere a lo lardo del siguiente año después de haberse quedado ciega</p>
                        </div>
                        <div class="col-lg-7" style="font-size: 20px; line-height: 25px">
                            debido a:
                            <ul>
                                <li>Carencia de vitamina a</li>
                                <li>Sarampión</li>
                                <li>Conjuntivitis del neonato</li>
                                <li>retinopatía del neonato</li>
                                <li>cataratas</li>
                                <li>glaucoma</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <p>La visión es fundamental en la etapa de aprendizaje durante la infancia. Según la OMS, un 80% de los problemas de aprendizaje en niños es causado por deficiencias visuales que, en un alto porcentaje, se pueden corregir con lentes o curar con simples intervenciones. Pruebas tan sencillas como la medida de la agudeza visual, permitirían captar estas anomalías y tratarlas a tiempo, sin embargo, sin profesionales ni medios no pueden llevarlo a cabo.</p>

            <img src="<?=Yii::app()->theme->baseUrl."/"; ?>images/tabla.png" class="center-block" style="margin-top: 40px; margin-bottom: 40px;">

            <div class="row">
                <div class="col-lg-6 text-justify">
                    <h2>¿cómo lo hacemos?</h2>

                    <p>Desplazamos médicos oftalmólogos y optometristas a los países subsaharianos asociados para que impartan la formación, tras un trabajo documental previo elaborado conjuntamente con sus Ministerios de Salud y Universidades, donde se definen sus prioridades y necesidades específicas. A raíz de este documento se deciden qué especialidades se deben impartir y se crean los contenidos de los cursos adaptándolos a limitados recursos tecnológicos que cada país dispone. Cada uno se basa en Masters de Universidades Europeas. Cada uno tiene una duración de dos años, periodo en el que desplazamos a 5 o 6 expediciones (dos semanas cada una) para impartirlo, salvo en algunas especialidades, como por ejemplo la pediatría, en la cual ofrecemos una extensión de 1 año para ampliar y consolidar el conocimiento en esta materia. Trabajamos para conseguir la homologación de nuestra formación por parte de los Ministerio de Salud y Educación de los dos países donde actuamos en estos momentos (Mozambique y Senegal), esperamos obtenerla durante 2015.</p>

                    <p class="lead">"Les formamos en su propio medio, adaptándonos a sus necesidades y utilizando sus propios recursos, aunque estos sean más arcaicos que los que usamos en occidente"</p>

                    <p>Las sesiones oftalmológicas son básicamente prácticas y se acompañan de sesiones clínicas. Aprovechando las visitas de los médicos de OCULARIS, los hospitales colaboradores citan a sus pacientes que presentan enfermedades más graves de la especialidad a formar, como por ejemplo tumores, estrabismo agudo, etc. Los pacientes reciben asistencia por parte de ambos profesionales, europeos y africanos, que de la mano planifican las pruebas preparatorias necesarias y realizan las cirugías conjuntamente. Con esta actividad se garantiza una formación in-situ que permite a los médicos locales adquirir nuevos conocimientos y mejorar sus técnicas quirúrgicas. La formación teórica se imparte mediante material audiovisual de los diferentes procedimientos clínicos.</p>

                    <p>Los ópticos-optometristas tienen como principal objetivo la formación específica de oftalmólogos,  residentes, técnicos superiores en oftalmología (TSO) y a enfermeros. Los cursos impartidos son de refracción y de baja visión, discapacidades visuales que si no son detectadas y tratadas pueden degenerar en ceguera. También les forman para mejorar la gestión y administración de los centros de salud y en el montaje de lentes.</p>

                    <p>Una vez finalizadas las expediciones del equipo de OCULARIS, se evalúa el trabajo realizado y se lleva a cabo un seguimiento para conocer el estado de los pacientes tratados y poder así orientar a los médicos locales.</p>
                    <p>Estamos trabajando en la creación de una plataforma e-learning de libre acceso para estudiantes de oftalmología y optometría de las universidades y hospitales subsaharianos donde cooperamos.</p>

                </div>
                <div class="col-lg-6">
                    <blockquote class="green">
                        <h3>Mediante este método formativo contribuimos a:</h3>

                        <p>Que médicos y sanitarios aprendan especialidades que a día de hoy existen en su país.</p>

                        <p>Superado el curso, estarán en disposición de formar ellos mismos a sus futuras generaciones.</p>

                        <p>Aumentar la calidad de la atención visual dispensada en sus centros de salud.</p>

                        <p>Asistir a un mayor número de personas.</p>

                        <p>Mejorar la calidad de vida de su población.</p>

                        <p>Erradicar la pobreza extrema que las deficiencias visuales causan en los países en vías de desarrollo.</p>

                    </blockquote>
                </div>
            </div>

            </div>

		</div>

		<div class="columns container-fluid">
			<div class="container">
				<div class="row">
				<div class="col-md-4">
					<?php $this->widget('modules.rssReader.components.widgets.RssReaderWidget',
								array('url' => 'http://ocularisassociacio.blogspot.com/feeds/posts/default?alt=rss', 'limit' => (int)Yii::app()->themeConfig->get('rssLimit'))); ?>
				</div>

				<div class="col-md-4">
					<div id="soci">
						<h3><?=Yii::t('SocioModule.main', 'Become a associate of Ocularis')?></h3>

						<p class="image-box text-center">
							<img src="<?=Yii::app()->theme->baseUrl;?>/images/hazteSocioMin.png" class="img-responsive" alt="Fes-te soci">
						</p>

						<p>
							El apoyo individual de personas como tú nos permite formar a médicos oftalmólogos y a ópticos para que sepan curar y devolver la vista a quienes más lo necesitan. Con tu aportación tú puedes devolver un vida digna a las personas que hoy en día no pueden valerse por sí solas, trabajar o ver la pizarra en su escuela. ¡Únete a OCULARIS!
						</p>
						<p>
							Tu aportación mensual nos permitirá seguir trabajando por un acceso a la salud visual universal e igualitaria y colaborarás en la reducción de la pobreza extrema en el África Subsahariana.
						</p>
						<p>
							¡Haz algo hoy extraordinario! Hazte socio de OCULARIS
						</p>
						<p class="content text-center">
							<?=CHtml::link(Yii::t('SocioModule.main', 'Become a associate'), array('/socio/socio/create'), array('class' => 'btn btn-primary'))?>
						</p>

					</div>
				</div>

				<div id="plugins" class="col-md-4">

					<div id="newsletter">
						<h3>Newsletter Ocularis</h3>

						<?php $form=$this->beginWidget('CActiveForm', array(
							'id'=>'newsletter-form',
							'action' => array('/mailchimp/default/index') )); ?>


							<div class="form-group">
								<?php echo CHtml::label(Yii::t('MailchimpModule.main', 'Name'), 'MailchimpForm_merge_vars_FNAME' ); ?>
								<?php echo CHtml::textField('MailchimpForm[merge_vars][FNAME]','', array('class' => 'form-control')); ?>
							</div>



								<div class="form-group">
									<?php echo CHtml::label('Email', 'MailchimpForm_email' ); ?>

										<div class="input-group">
											<?php echo CHtml::textField('MailchimpForm[email]','', array('class' => 'form-control')); ?>
											 <span class="input-group-btn">
												<?php echo CHtml::submitButton(Yii::t('MailchimpModule.main', "Register"), array('class' => 'btn btn-primary') ); ?>
											</span>
										</div>


											<?php echo CHtml::hiddenField("MailchimpForm[merge_vars][TIPUS]",'0'); ?>
											<?php echo CHtml::hiddenField("MailchimpForm[merge_vars][LANGUAGE]", strtoupper(Yii::app()->language) ); ?>

								</div>





						<?php $this->endWidget(); ?>


					</div>

					<div class="">

						<div class="facebook socialbox text-center col-sm-6 col-md-12">
							<div class="fb-like-box" data-href="https://www.facebook.com/ONG.OCULARIS" data-width="300" data-height="275" data-colorscheme="light" data-show-faces="true" data-header="true" data-stream="false" data-show-border="true"></div>
							<div id="fb-root"></div>
							<script>(function(d, s, id) {
							  var js, fjs = d.getElementsByTagName(s)[0];
							  if (d.getElementById(id)) return;
							  js = d.createElement(s); js.id = id;
							  js.src = "//connect.facebook.net/es_LA/sdk.js#xfbml=1&appId=293896544125555&version=v2.0";
							  fjs.parentNode.insertBefore(js, fjs);
							}(document, 'script', 'facebook-jssdk'));</script>
						</div>

						<div class="twitter socialbox text-center  col-sm-6 col-md-12">
							<a class="twitter-timeline" href="https://twitter.com/ONGOCULARIS" data-widget-id="389337807291834368">Tweets por @ONGOCULARIS</a>
							<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
						</div>

					</div>

				</div>

				</div>

			</div>

		</div>

		<div id="teaser">
			<div class="container">
				<?=Yii::app()->themeConfig->get('home-text')?>
			</div>
		</div>
