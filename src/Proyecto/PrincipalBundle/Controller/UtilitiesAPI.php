<?php

namespace Proyecto\PrincipalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;

use Proyecto\PrincipalBundle\Entity\Usuario;
use Proyecto\PrincipalBundle\Entity\Autores;
use Proyecto\PrincipalBundle\Entity\Sistema;
use Proyecto\PrincipalBundle\Entity\Proyecto;

class UtilitiesAPI extends Controller {
		
	public static function getGalleryResources($gallery,$class){
		$locale = UtilitiesAPI::getLocale($class);
	
		$em = $class->getDoctrine()->getManager();

		$query = $em -> createQuery('SELECT d.resource,d.rank,r.name
    								 FROM ProyectoPrincipalBundle:CmsGalleryResource d,
    								      ProyectoPrincipalBundle:CmsResource r
   	 								 WHERE d.gallery      = :gallery
   	 								  and  d.resource = r.id
   	 								  and  r.published = :published
    								 ORDER BY d.rank ASC') 
    		   
    		   -> setParameter('gallery', $gallery)
    		   -> setParameter('published', 1);
		$array = $query -> getResult();

		return $array;
	}
	public static function getDataByEntity($entity,$inicial,$class){
		$locale = UtilitiesAPI::getLocale($class);
	
		$em = $class->getDoctrine()->getManager();
		
		$sql = 'SELECT d.id,d.name
    								 FROM ProyectoPrincipalBundle:'.$entity.' d
   	 								 WHERE d.lang      = :locale and
   	 								       d.published = :published
    								 ORDER BY d.name ASC';

		$query = $em -> createQuery($sql) 
    		   -> setParameter('locale', $locale)
			   -> setParameter('published', 1);

		$array = $query -> getResult();

		$helper = array();

		if($inicial==true){
			$helper[0] = 'Sin Definir';
		}

		for ($i=0; $i < count($array) ; $i++) { 
			$helper[$array[$i]['id']] = $array[$i]['name'];
		}
		
		$array = $helper;
		return $array;
	}
	public static function getDataByEntityWithoutLang($entity,$class){
		$locale = UtilitiesAPI::getLocale($class);
	
		$em = $class->getDoctrine()->getManager();
		

		$sql = 'SELECT d FROM ProyectoPrincipalBundle:'.$entity.' d
   	 			WHERE d.published = :published ORDER BY d.dateCreated ASC';

		$query = $em -> createQuery($sql) 
			   -> setParameter('published', 1);

		$array = $query -> getResult();

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
	public static function getSingleAttribute($entity,$attribute,$id, $class){

		$resultado = "";
        $em = $class->getDoctrine()->getManager();
       
		$locale = UtilitiesAPI::getLocale($class);
	
		$em = $class->getDoctrine()->getManager();
		
		$sql = 'SELECT d.'.$attribute.'
    								 FROM ProyectoPrincipalBundle:'.$entity.' d
   	 								 WHERE d.id = :id';

		$query = $em -> createQuery($sql) 
			   -> setParameter('id', $id);

		$array = $query -> getResult();

		if(count ($array)<1){
			$resultado = '-';
		}
		else{
			$resultado = $array[0][$attribute];
		}

		return $resultado;
	}
	public static function getRank($locale, $class){

        $em = $class->getDoctrine()->getManager();
       
        $qbCount = $em
			->createQueryBuilder()
			->select('count(g)')
			->from('ProyectoPrincipalBundle:CmsPage','g')
			->where("g.lang = '".$locale."'")	
        ;
        
        $recordCount = $qbCount
			->getQuery()
			->getSingleScalarResult()
        ;
		
		return $recordCount + 1;
	}
	public static function getLocale($class){
		
		$request = $class->getRequest();
		$locale = $request->getLocale();
		if($locale=='es')return 0;
		else return 1;
		
	}
	public static function getConfig($type,$class){

		$array =  array( );
		if($type == 'pages'){
			//$array['idtype'] = 0;
			$array['list'] = 'Mostrar Páginas';
			$array['create'] = 'Añadir Página';
			$array['edit'] = 'Editar Pagina';
			$array['translate'] = 'Traducción de Página';
			$array['type'] = $type;
		}
		else if($type == 'exhibitions'){
			$array['idtype'] = 0;
			$array['list'] = 'Mostrar Exposiciones';
			$array['create'] = 'Añadir Exposicion';
			$array['edit'] = 'Editar Exposicion';
			$array['translate'] = 'Traducir Exposicion';
			$array['type'] = $type;
		}
		else if($type == 'events'){
			$array['idtype'] = 1;
			$array['list'] = 'Mostrar Espéctaculos';
			$array['create'] = 'Añadir Espéctaculo';
			$array['edit'] = 'Editar Espéctaculo';
			$array['translate'] = 'Traducir Espéctaculo';
			$array['type'] = $type;
		}
		else if($type == 'programs'){
			$array['idtype'] = 2;
			$array['list'] = 'Mostrar Talleres';
			$array['create'] = 'Añadir Taller';
			$array['edit'] = 'Editar Taller';
			$array['translate'] = 'Traducir Taller';
			$array['type'] = $type;
		}
		else if($type == 'images'){
			$array['idtype'] = 3;
			$array['list'] = 'Mostrar Imágenes';
			$array['create'] = 'Añadir Imagen';
			$array['edit'] = 'Editar Imagen';
			$array['translate'] = 'Traducir Imagen';
			$array['type'] = $type;
		}
		else if($type == 'backgrounds'){
			$array['idtype'] = 4;
			$array['list'] = 'Mostrar Fondos';
			$array['create'] = 'Añadir Fondo';
			$array['edit'] = 'Editar Fondo';
			$array['translate'] = 'Traducir Fondo';
			$array['type'] = $type;
		}
		else if($type == 'links'){
			$array['list'] = 'Mostrar Enlaces';
			$array['create'] = 'Añadir Enlace';
			$array['edit'] = 'Editar Enlace';
			$array['translate'] = 'Traducir Enlace';
			$array['type'] = $type;
		}
		else if($type == 'setting'){
			$array['list'] = 'Mostrar Opciones';
			$array['create'] = 'Añadir Opcion';
			$array['edit'] = 'Editar Opción';
			$array['translate'] = 'Traducir Opcion';
			$array['type'] = $type;
		}
		else if($type == 'reservation'){
			$array['list'] = 'Mostrar Reservas';
			$array['type'] = $type;
		}
		else if($type == 'gallery'){
			//$array['idtype'] = 0;
			$array['list'] = 'Mostrar Galerías';
			$array['create'] = 'Añadir Galería';
			$array['edit'] = 'Editar Galería';
			$array['translate'] = 'Traducción de Galería';
			$array['type'] = $type;
		}
		else if($type == 'text-resource'){
			//$array['idtype'] = 0;
			$array['list'] = 'Mostrar Textos Asociados';
			$array['create'] = 'Añadir Texto Asociado';
			$array['edit'] = 'Editar Texto Asociado';
			$array['translate'] = 'Traducción Texto Asociado';
			$array['type'] = $type;
		}
		return $array;
	}
	public static function generaTrans($class){
		echo"<div>\n";
		for ($i=43; $i < 300; $i++) { 
			echo "            <trans-unit id=".$i.">\n                <source></source>\n                <target></target>\n            </trans-unit>\n";
		}
		echo"\n</div>";
		exit;
	}
	public static function getFilter($clase,$class) {
		$data = $class -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:'.$clase) -> findAll();

		$array = array();
		for ($i = 0; $i < count($data); $i++) {
			$array[$data[$i] -> getId()] = $data[$i] -> getName();
		}
		return $array;
	}
	public static function getFilterData($data,$class) {
		$array = array();
		for ($i = 0; $i < count($data); $i++) {
			$array[$data[$i] -> getId()] = $data[$i] -> getName();
		}
		return $array;
	}

	public static function removeData($id,$class){
			
		$object = $class -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:Data') -> find($id);
		$em = $class -> getDoctrine() -> getManager();
		$em->remove($object);
		$em->flush();
	}

	
	
	public static function getDefaultContent($seccion,$subseccion,$class){

		//$parameters = UtilitiesAPI::getParameters($class);
		$menu = UtilitiesAPI::getMenu($seccion,$subseccion,$class);
		$user = UtilitiesAPI::getActiveUser($class);
		//$notifications = UtilitiesAPI::getNotifications($user);
		
		$array = array('menu' => $menu,'user' => $user);
		
		return $array;
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

	public static function getMenu($seccion,$subseccion, $this) {
		$menu = array('seccion' => $seccion,'subseccion' => $subseccion );
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

	public static function getNotifications($user) {

		$notifications = null;

		if ($user != NULL) {
			$notifications = array();
			$notifications[0]['texto'] = 'Espacio reducido';
			$notifications[0]['numero'] = '40%';
		}

		return $notifications;
	}

	public static function procesaUsuario($tipo, $nombre, $apellido, $nombreusuario, $contrasenia, $email, $descripcion, $path, $class) {

		$factory = $class -> get('security.encoder_factory');
		$user = null;

		if ($tipo == 0) {
			$user = new Usuario();
			$encoder = $factory -> getEncoder($user);
			$password = $encoder -> encodePassword($contrasenia, $user -> getSalt());
			$user -> setPassword($password);

		} else {
			$user = $class -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:Usuario') -> find( UtilitiesAPI::getActiveUser($class) -> getId());
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
		$user -> setEmail($email);
		$user -> setPath($path);
		$user -> setDescripcion($descripcion);

		$em = $class -> getDoctrine() -> getManager();
		$em -> persist($user);
		$em -> flush();
	}
	 public static function convertirFechaNormal($fechaOriginal, $class) {
	
	 $fechaOriginal = trim($fechaOriginal);
	 $arreglo1 = explode(" ", $fechaOriginal);
	 $arreglo = explode("-", $arreglo1[0]);
	 $fecha = new \DateTime();
	 $fecha -> setDate($arreglo[2], $arreglo[1], $arreglo[0]);
	 $arreglo = explode(":", $arreglo1[1]);
	 $fecha -> setTime ( $arreglo[0], $arreglo[1], $arreglo[2] );
	 
	 return $fecha;
	 }

	 public static function convertirAFechaNormal($fechaOriginal, $class) {

	 $fechaOriginal = new \DateTime($fechaOriginal);
	 return date_format($fechaOriginal, 'd/m/Y'); ;
	 }
	 public static function fechaHoy($class) {
	 	$hoy = getdate();
		$fecha = $hoy['year'] . '-' . $hoy['mon'] . '-'.$hoy['mday'];
		
	 	return $fecha;
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
