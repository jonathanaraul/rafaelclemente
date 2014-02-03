<?php

namespace Proyecto\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Proyecto\PrincipalBundle\Entity\CmsGalleryResource;
use Proyecto\PrincipalBundle\Entity\CmsTextResource;


use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class UtilitiesAPI extends Controller {

	/**
	* Constantes que representan los ids en la base de datos de las paginas especiales
	*/
	const NOTICIAS_ES = 1;
	const CARTELERA_ES = 37;
	const TALLERES_ES = 5;
	const CALENDARIO_ES = 38;

	const NOTICIAS_EN = 39;
	const CARTELERA_EN = 41;
	const TALLERES_EN = 40;
	const CALENDARIO_EN = 42;

	const TIPO_NOTICIAS = 0;
	const TIPO_CARTELERA = 1;
	const TIPO_TALLERES = 2;

	public static function  findTextResource($resource, $class){
		//$locale = UtilitiesAPI::getLocale($class);
		$em = $class->getDoctrine()->getManager();
		$locale = UtilitiesAPI::getLocale($class);

		$sql = 'SELECT d  FROM ProyectoPrincipalBundle:CmsTextResource d
   	 					  WHERE 
   	 					  		d.resource = :resource and
   	 					  		d.lang      = :locale and
   	 						    d.published = :published';
	

		
		$query = $em -> createQuery($sql ) 
		 	   -> setParameter('resource', $resource)
    		   -> setParameter('locale', $locale)
			   -> setParameter('published', 1);

		$array = $query -> getResult();
		if($array!=null)
		$array = $array[count($array)-1];
		else $array = null;
		return $array;
	}
	public static function setFriendlyNameEntity($entity,$class){

    	$em = $class->getDoctrine()->getManager();
   		$objects = $em->getRepository('ProyectoPrincipalBundle:'.$entity) -> findAll();

   		for ($i=0; $i < count($objects) ; $i++) { 
   			$objects[$i]->setFriendlyName(UtilitiesAPI::getFriendlyName($objects[$i]->getName(),$class));
   		}
		//var_dump($objects);
		//exit;
		$em->flush();
	}
	public static function getGalleryResources($gallery, $class){
		//$locale = UtilitiesAPI::getLocale($class);
		$em = $class->getDoctrine()->getManager();

		$query = $em -> createQuery('SELECT r
    								 FROM ProyectoPrincipalBundle:CmsGalleryResource d,
    								      ProyectoPrincipalBundle:CmsResource r
   	 								 WHERE d.gallery      = :gallery
   	 								  and  d.resource = r.id
   	 								  and  r.published = :published
    								 ORDER BY d.rank ASC') 
    		   
    		   -> setParameter('gallery', $gallery)
    		   -> setParameter('published', 1);
		$array = $query -> getResult();

		$array = UtilitiesAPI::devuelveRecursosExistentes($array,$class);

		return $array;
	}
	public static function devuelveRecursosExistentes($array,$class){
		$helper = array();
		$contador = 0;

		for ($i=0; $i < count($array) ; $i++) { 
			
			if($array[$i]->getPath()!= null && $array[$i]->getPath()!= '' && $array[$i]->getPath()!= 'initial'){
				if (file_exists($array[$i]->getWebPath())) {
   					$helper[$contador] = $array[$i];
   					$contador++;
				}
			}
		}

		return $helper;
	}
	public static function getExhibitions($class){
		$locale = UtilitiesAPI::getLocale($class);
	
		$em = $class->getDoctrine()->getManager();
		
		$query = $em -> createQuery('SELECT d
    								 FROM ProyectoPrincipalBundle:CmsArticle d
   	 								 WHERE d.lang      = :locale and
   	 								       d.published = :published
    								 ORDER BY d.date DESC') 
    		   -> setParameter('locale', $locale)
			   -> setParameter('published', 1);

		$array = $query -> getResult();
		
		return $array;
	}

	public static function letraDia($cadena,$class){
		$array = array();

		if(UtilitiesAPI::getLocale($class)==0)
		$array = array('D','L','M','M','J','V','S' );
		else{
		$array = array('S','M','T','W','T','F','S' );
		}
		
		return $array[$cadena];
	}
	public static function esListado($idpage, $class){
		
		$listado = null;
		
		if($idpage== UtilitiesAPI::NOTICIAS_ES 
		 ||$idpage== UtilitiesAPI::CARTELERA_ES 
		 ||$idpage== UtilitiesAPI::TALLERES_ES  
		 ||$idpage== UtilitiesAPI::CALENDARIO_ES  )
		 {

			if($idpage == UtilitiesAPI::NOTICIAS_ES){
				$listado = 'noticias';
			}
			else if($idpage == UtilitiesAPI::CARTELERA_ES){
				$listado = 'cartelera';
			}
			else if($idpage == UtilitiesAPI::TALLERES_ES){
				$listado = 'talleres';
			}
			else if($idpage == UtilitiesAPI::CALENDARIO_ES){
				$listado = 'calendario';
			}
		 }
		else if($idpage== UtilitiesAPI::NOTICIAS_EN 
		      ||$idpage== UtilitiesAPI::CARTELERA_EN 
		      ||$idpage== UtilitiesAPI::TALLERES_EN  
		      ||$idpage== UtilitiesAPI::CALENDARIO_EN  )
		 {
			
			if($idpage == UtilitiesAPI::NOTICIAS_EN){
				$listado = 'noticias';
			}
			else if($idpage == UtilitiesAPI::CARTELERA_EN){
				$listado = 'cartelera';
			}
			else if($idpage == UtilitiesAPI::TALLERES_EN){
				$listado = 'talleres';
			}
			else if($idpage == UtilitiesAPI::CALENDARIO_EN){
				$listado = 'calendario';
			}
		 }
	
	return $listado;
	}
	public static function getLocale($class){
		
		$request = $class->getRequest();
		$locale = $request->getLocale();

		if($locale=='es')return 0;
		else if($locale=='en')return 1;
		else if($locale=='fr')return 2;
		else if($locale=='de')return 3;
		else if($locale=='ch')return 4;
		
	}

	public static function getFriendlyName($title,$class){
		$friendlyName = strtolower($title);
		$friendlyName =  str_replace("á", "a", $friendlyName);
		$friendlyName =  str_replace("é", "e", $friendlyName);
		$friendlyName =  str_replace("í", "i", $friendlyName);
		$friendlyName =  str_replace("ó", "o", $friendlyName);
		$friendlyName =  str_replace("ú", "u", $friendlyName);
		$friendlyName =  str_replace("'", "", $friendlyName);
		$friendlyName =  str_replace('"', "", $friendlyName);
		$friendlyName =  str_replace("ñ", "n", $friendlyName);
		$friendlyName =  str_replace("_", " ", $friendlyName);
		$friendlyName =  str_replace(".", " ", $friendlyName);
		$friendlyName =  str_replace(":", " ", $friendlyName);
		$friendlyName =  str_replace("-", " ", $friendlyName);
		$friendlyName =  str_replace("           ", " ", $friendlyName);
		$friendlyName =  str_replace("          ", " ", $friendlyName);
		$friendlyName =  str_replace("         ", " ", $friendlyName);
		$friendlyName =  str_replace("        ", " ", $friendlyName);
		$friendlyName =  str_replace("       ", " ", $friendlyName);
		$friendlyName =  str_replace("      ", " ", $friendlyName);
		$friendlyName =  str_replace("     ", " ", $friendlyName);
		$friendlyName =  str_replace("    ", " ", $friendlyName);
		$friendlyName =  str_replace("   ", " ", $friendlyName);
		$friendlyName =  str_replace("  ", " ", $friendlyName);
		$friendlyName =  str_replace(" ", "-", $friendlyName);
		
		return $friendlyName;
	}
	public static function sendMail($nombreEvento,$datosUsuario,$class)
	{
    $message = \Swift_Message::newInstance()
        ->setSubject('Nueva Reservación')
        ->setFrom('hjimenez45@gmail.com')
        ->setTo('jonathan.araul@gmail.com')
        ->setBody(
            $class->renderView(
                'ProyectoFrontBundle:Default:email.txt.twig',
                array('nombreEvento' => $nombreEvento,'datosUsuario'=>$datosUsuario)
            )
        )
    ;
    $class->get('mailer')->send($message);

    return true;
	}
	public static function removeData($id,$class){
			
		$object = $class -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:Data') -> find($id);
		$em = $class -> getDoctrine() -> getManager();
		$em->remove($object);
		$em->flush();
	}

    public static function getMenuElements($class)
    {
    	$locale = UtilitiesAPI::getLocale($class);
	
		$em = $class->getDoctrine()->getManager();
		
		$query = $em -> createQuery('SELECT d
    								 FROM ProyectoPrincipalBundle:CmsPage d
   	 								 WHERE d.lang      = :locale and
   	 								       d.published = :published
    								 ORDER BY d.rank ASC') 
    		   -> setParameter('locale', $locale)
			   -> setParameter('published', 1);

		$array = $query -> getResult();
		
		return $array;
    }
	public static function getDefaultContent($menu,$class){

		$user = UtilitiesAPI::getActiveUser($class);
		$acceso = UtilitiesAPI::getAccess($user,$class);
		$menuElements = UtilitiesAPI::getMenuElements($class);
		
		$array = array('menu' => $menu,'usuario' => $user, 'acceso'=>$acceso, 'menuElements'=>$menuElements);
		return $array;
	}
	public static function getAccess($user,$class){

		$acceso = null;
		if($user == null){
			$error = null;
			$ultimo_nombreusuario = null;
			$peticion = $class -> getRequest();
			$sesion = $peticion -> getSession();
			// obtiene el error de inicio de sesión si lo hay
			if ($peticion -> attributes -> has(SecurityContext::AUTHENTICATION_ERROR))
				$error = $peticion -> attributes -> get(SecurityContext::AUTHENTICATION_ERROR);
			else
				$error = $sesion -> get(SecurityContext::AUTHENTICATION_ERROR);

			$acceso = array('ultimo_nombreusuario' => $sesion -> get(SecurityContext::LAST_USERNAME), 'error' => $error);
		}
		return $acceso;
	}

	public static function getAutors($class) {
		$autors = $class -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:Autores') -> findAll();
		$users = array();
		for ($i = 0; $i < count($autors); $i++) {
			$users[$i] = $autors[$i] -> getUsuario();
			
		}

		/*
		 * Añadir exception de no encontrar parameters
		 if (!$product) {
		 throw $this->createNotFoundException(
		 'No product found for id '.$id
		 );
		 }
		 *
		 */
		return $users;
	}

	public static function getParameters($class) {
		$parameters = $class -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:Sistema') -> find(1);

		/*
		 * Añadir exception de no encontrar parameters
		 if (!$product) {
		 throw $this->createNotFoundException(
		 'No product found for id '.$id
		 );
		 }
		 *
		 */
		return $parameters;
	}

	public static function getMenu($seccion,$subseccion,$titulo, $this) {
		$menu = array('seccion' => $seccion,'subseccion' => $subseccion, 'titulo' => $titulo );
		// = $this->getDoctrine()->getRepository('ProyectoPrincipalBundle:Sistema')->find(1);

		/*
		 * Añadir exception de no encontrar parameters
		 if (!$product) {
		 throw $this->createNotFoundException(
		 'No product found for id '.$id
		 );
		 }
		 *
		 */
		return $menu;
	}

	public static function getActiveUser($class) {

		$user = $class -> getUser();

		if ($user != NULL && false === $class -> get('security.context') -> isGranted('ROLE_USER')) {
			$user = null;
		}

		return $user;
	}

	public static function isUser($class) {

		$user = $class -> getUser();

		if ($user != NULL && false === $class -> get('security.context') -> isGranted('ROLE_USER') ) {
			throw new AccessDeniedException();
		}

		return true;
	}

	public static function getNotifications($user) {

		$notifications = null;

		if ($user != NULL) {
			$notifications = array();
			$notifications[0]['texto'] = 'Espacio reducido';
			$notifications[0]['numero'] = '40%';
		}

		return $notifications;
	}

	public static function procesaUsuario($tipo, $nombre, $apellido, $nombreusuario, $contrasenia, $sexo, $email, $descripcion, $path, $class) {

		$factory = $class -> get('security.encoder_factory');
		$user = null;

		if ($tipo == 0) {
			$user = new User();
			$encoder = $factory -> getEncoder($user);
			$password = $encoder -> encodePassword($contrasenia, $user -> getSalt());
			$user -> setPassword($password);

		} else {
			$user = $class -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:User') -> find( UtilitiesAPI::getActiveUser($class) -> getId());
			if (!$user) {
				throw $class -> createNotFoundException('No se encontro el usuario ' . UtilitiesAPI::getActiveUser($class) -> getId());
			}
			if (strlen($contrasenia) >= 8) {
				$encoder = $factory -> getEncoder($user);
				$password = $encoder -> encodePassword($contrasenia, $user -> getSalt());
				$user -> setPassword($password);
			}
		}

		$user -> setNombre($nombre);
		$user -> setApellido($apellido);
		$user -> setUsername($nombreusuario);
		$user -> setSexo($sexo);
		$user -> setEmail($email);
		$user -> setPath($path);
		$user -> setDescripcion($descripcion);

		$em = $class -> getDoctrine() -> getManager();
		$em -> persist($user);
		$em -> flush();
	}
	 public static function fechaHoy($class) {
	 	$hoy = getdate();
		$fecha = $hoy['year'] . '-' . $hoy['mon'] . '-'.$hoy['mday'];
		
	 	return $fecha;
	 }
	 public static function fechaInicioFin($fecha,$class) {
		$array= array('inicio' => $fecha.' 00:00:00', 'fin' => $fecha.' 23:59:59');
	 	return $array;
	 }
	 public static function obtenerFechaSistema($class) {

	 	$dia = $hoy['mday'];
	 	$hora = $hoy['hours'];
	 	$minuto = $hoy['minutes'];

	 	$dias = array('Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado');
	 	$dsemana = $hoy['wday'];

	 	$fecha = $dias[$dsemana] . ", " . $dia . " de " . $meses[$mes] . ' de ' . $anio;
	 //.' - '.$hora.':'.$minuto;
	 	return $fecha;
	 }
	 public static function fechaPresentacion($fechaOriginal, $class) {

	 	$mes = intval(date_format($fechaOriginal, 'm')) -1;
	 	$meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
	 	$fecha = 'Día '.date_format($fechaOriginal, 'd').' de '.$meses[$mes].' de '.date_format($fechaOriginal, 'Y').', '.date_format($fechaOriginal, 'H:i').' horas';
	 	
	 	return $fecha;
	 }

	/*

	 public static function obtenerFechaSistema($class) {
	 $hoy = getdate();
	 $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
	 $anio = $hoy['year'];
	 $mes = intval($hoy['mon']) - 1;
	 $dia = $hoy['mday'];
	 $hora = $hoy['hours'];
	 $minuto = $hoy['minutes'];

	 $dias = array('Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado');
	 $dsemana = $hoy['wday'];

	 $fecha = $dias[$dsemana] . ", " . $dia . " de " . $meses[$mes] . ' de ' . $anio;
	 //.' - '.$hora.':'.$minuto;
	 return $fecha;
	 }

	 public static function obtenerFechaCastellanizada($class) {
	 $hoy = getdate();
	 $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
	 $anio = $hoy['year'];
	 $mes = intval($hoy['mon']) - 1;
	 $dia = $hoy['mday'];
	 $hora = $hoy['hours'];
	 $minuto = $hoy['minutes'];
	 $fecha = $dia . " de " . $meses[$mes] . ' del ' . $anio;
	 //.' - '.$hora.':'.$minuto;
	 return $fecha;
	 }

	 public static function obtenerFechaCastellanizada2($fechaOriginal, $class) {

	 $arreglo = explode("-", $fechaOriginal);
	 $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
	 $mes = intval($arreglo[1]) - 1;
	 $fecha = $arreglo[0] . " de " . $meses[$mes] . ' del ' . $arreglo[2];

	 return $fecha;
	 }

	 public static function obtenerFechaCastellanizada3($fechaOriginal, $class) {

	 $arreglo = explode("-", $fechaOriginal);
	 $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
	 $mes = intval($arreglo[1]) - 1;
	 $fecha = $meses[$mes] . ' del ' . $arreglo[2];

	 return $fecha;
	 }

	 public static function obtenerFechaCastellanizada4($fechaOriginal, $class) {

	 $arreglo = explode("/", $fechaOriginal);
	 $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
	 $mes = intval($arreglo[1]) - 1;
	 $fecha = $arreglo[0] . " de " . $meses[$mes] . ' del ' . $arreglo[2];

	 return $fecha;
	 }

	 public static function obtenerNombreMes($fecha, $class) {
	 $hoy = getdate();
	 $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

	 $mes = intval($fecha['mon']) - 1;
	 return $mes;
	 }

	 public static function obtenerFechaNormal($class) {
	 $hoy = getdate();
	 $anio = $hoy['year'];
	 $mes = $hoy['mon'];
	 $dia = $hoy['mday'];
	 $fecha = $dia . "/" . $mes . '/' . $anio;
	 //.' - '.$hora.':'.$minuto;
	 return $fecha;
	 }

	 public static function obtenerFechaNormal2($class) {
	 $hoy = getdate();
	 $anio = $hoy['year'];
	 $mes = $hoy['mon'];
	 $dia = $hoy['mday'];
	 $fecha = $dia . "-" . $mes . '-' . $anio;
	 //.' - '.$hora.':'.$minuto;
	 return $fecha;
	 }

	 public static function obtenerFechaNormal3($class) {
	 $hoy = getdate();
	 $anio = $hoy['year'];
	 $mes = $hoy['mon'];
	 $dia = $hoy['mday'];
	 $fecha = $anio . "-" . $mes . '-' . $dia;
	 //.' - '.$hora.':'.$minuto;
	 return $fecha;
	 }

	 public static function obtenerMesYAnio($class) {
	 $hoy = getdate();
	 return array($hoy['year'], $hoy['mon']);
	 }

	 public static function convertirFechaNormal($fechaOriginal, $class) {

	 $arreglo = explode("-", $fechaOriginal);
	 $fecha = new \DateTime();
	 $fecha -> setDate($arreglo[2], $arreglo[1], $arreglo[0]);
	 return $fecha;
	 }

	 public static function convertirFechaNormal3($fechaOriginal, $class) {
	 $arreglo = explode("/", $fechaOriginal);
	 $fecha = new \DateTime();
	 $fecha -> setDate($arreglo[2], $arreglo[1], $arreglo[0]);
	 return $fecha;
	 }

	 public static function convertirFechaNormal2($fechaOriginal, $class) {
	 $fechaOriginal = trim($fechaOriginal);
	 $arreglo1 = explode(" ", $fechaOriginal);
	 $arreglo = explode("-", $arreglo1[0]);
	 $fecha = new \DateTime();
	 $fecha -> setDate($arreglo[2], $arreglo[1], $arreglo[0]);
	 return $fecha;
	 }

	 public static function convertirAFechaNormal($fechaOriginal, $class) {

	 $fechaOriginal = new \DateTime($fechaOriginal);
	 return date_format($fechaOriginal, 'd/m/Y'); ;
	 }

	 public static function convertirAFormatoSQL($fechaOriginal, $class) {

	 $arreglo = explode("-", $fechaOriginal);
	 if ($arreglo[1] < 10)
	 $arreglo[1] = '0' . $arreglo[1];
	 if ($arreglo[0] < 10)
	 $arreglo[0] = '0' . $arreglo[0];
	 $fecha = $arreglo[2] . '-' . $arreglo[1] . '-' . $arreglo[0] . ' 00:00:00';

	 return $fecha;

	 }

	 public static function obtenerFechasFormatoSQL($anio, $mes, $class) {

	 if ($mes < 10)
	 $mes = '0' . $mes;
	 $dia = '01';

	 $fechaInicial = $anio . '-' . $mes . '-' . $dia . ' 00:00:00';
	 $dia = '31';
	 $fechaFinal = $anio . '-' . $mes . '-' . $dia . ' 00:00:00';

	 $arreglo = array($fechaInicial, $fechaFinal);

	 return $arreglo;

	 }

	 public static function convertirAFormatoSQL2($fechaOriginal, $class) {

	 $arreglo = explode("-", $fechaOriginal);
	 if ($arreglo[1] < 10)
	 $arreglo[1] = '0' . $arreglo[1];
	 if ($arreglo[0] < 10)
	 $arreglo[0] = '0' . $arreglo[0];
	 $fecha = $arreglo[2] . '-' . $arreglo[1] . '-' . $arreglo[0];

	 return $fecha;

	 }

	 public static function convertirAFormatoSQL3($fechaOriginal, $class) {

	 $arreglo = explode("-", $fechaOriginal);

	 $fecha = $arreglo[2] . '/' . $arreglo[1] . '/' . $arreglo[0];

	 return $fecha;

	 }

	 public static function convertirAFormatoSQL4($fechaOriginal, $class) {

	 $arreglo = explode("-", $fechaOriginal);
	 $fecha = $arreglo[2] . '-' . $arreglo[1] . '-' . $arreglo[0] . ' 00:00:00';

	 return $fecha;

	 }

	 public static function primerDiaMes($fechaOriginal, $class) {

	 $arreglo = explode("-", $fechaOriginal);
	 $fecha = $arreglo[2] . '-' . $arreglo[1] . '-01 00:00:00';

	 return $fecha;

	 }

	 public static function primerDiaMesSiguiente($fechaOriginal, $class) {

	 $arreglo = explode("-", $fechaOriginal);
	 $mes = intval($arreglo[1]);
	 $anio = intval($arreglo[2]);

	 if ($mes == 12) {
	 $mes = "01";
	 $anio++;
	 } else {
	 $mes++;
	 if ($mes < 9)
	 $mes = "0" . $mes;
	 }

	 $fecha = $anio . '-' . $mes . '-01 00:00:00';

	 return $fecha;

	 }

	 public static function sumarTiempo($fechaOriginal, $dia, $mes, $anio, $class) {

	 $arreglo = explode("-", $fechaOriginal);

	 $fecha = new \DateTime();
	 $fecha -> setDate($arreglo[2], $arreglo[1], $arreglo[0]);
	 $fecha -> setTime(0, 0, 0);
	 $periodo = 'P' . $anio . 'Y' . $mes . 'M' . $dia . 'D';
	 $fecha -> add(new \DateInterval($periodo));

	 $fecha = date_format($fecha, 'Y-m-d H:i:s'); ;
	 return $fecha;

	 }
	 */
}
