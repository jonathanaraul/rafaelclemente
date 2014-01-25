<?php

namespace Proyecto\PrincipalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Util\StringUtils;
use Proyecto\PrincipalBundle\Entity\Usuario;

class UserController extends Controller {
	
	public function listAction() {
		$firstArray = UtilitiesAPI::getDefaultContent('TU INFORMACIÓN', 'Mostrar Información', $this);
		$secondArray = array();
		                      
		$array = array_merge($firstArray, $secondArray);
		
		return $this -> render('ProyectoPrincipalBundle:User:List.html.twig', $array);
	}
	
	public function editAction() {
		$firstArray = UtilitiesAPI::getDefaultContent('TU INFORMACIÓN', 'Mostrar Información', $this);
		$secondArray = array('accion'=>'edicion');
		$secondArray['url'] = $this -> generateUrl('proyecto_principal_user_edit');
		                      
		$array = array_merge($firstArray, $secondArray);
		return UserController::procesar($array,$this);
	}
	
	public function newAction() {
		$firstArray = UtilitiesAPI::getDefaultContent('TU INFORMACIÓN', 'Nuevo Información', $this);
		$secondArray = array('accion'=>'registro');
		$secondArray['url'] = $this -> generateUrl('proyecto_principal_user_new');
		                      
		$array = array_merge($firstArray, $secondArray);
		return UserController::procesar($array,$this);
	}

	public static function procesar($array,$class){

		if($array['accion'] == 'registro' )$data = new Usuario();
		else $data = $array['user'];
		
		$form = $class -> createFormBuilder($data) 
		-> add('username', 'text') 
		-> add('password', 'repeated', array('first_name' => 'password', 'second_name' => 'confirm', 'type' => 'password', )) 
		-> add('email', 'email') 
		-> getForm();

		if ($class -> getRequest() -> isMethod('POST')) {
			$form -> bind($class -> getRequest());

			if ($form -> isValid()) {

				$em = $class -> getDoctrine() -> getManager();
				$factory = $class -> get('security.encoder_factory');
				$encoder = $factory -> getEncoder($data);
				$password = $encoder -> encodePassword($data -> getPassword(), $data -> getSalt());
				$data -> setPassword($password);
				$em -> persist($data);
				$em -> flush();
				
				return $class->redirect($class->generateUrl('proyecto_principal_homepage',array('_locale' => 'es')));
				
			}
		}
		$array['form'] = $form -> createView();
		return $class -> render('ProyectoPrincipalBundle:User:New-Edit.html.twig', $array);
	}
	public function loginAction() {

		$error = NULL;
		$ultimo_nombreusuario = null;

		$peticion = $this -> getRequest();
		$sesion = $peticion -> getSession();
		// obtiene el error de inicio de sesión si lo hay
		if ($peticion -> attributes -> has(SecurityContext::AUTHENTICATION_ERROR))
			$error = $peticion -> attributes -> get(SecurityContext::AUTHENTICATION_ERROR);
		else
			$error = $sesion -> get(SecurityContext::AUTHENTICATION_ERROR);

		$firstArray = array();//UtilitiesAPI::getDefaultContent('Usuarios', 'Acceso', 'Acceso', 'Ingrese su nombre de usuario y su contraseña', $this);
		$secondArray = array('ultimo_nombreusuario' => $sesion -> get(SecurityContext::LAST_USERNAME), 'error' => $error);

		$array = array_merge($firstArray, $secondArray);
		//return $this -> render('ProyectoPrincipalBundle:Principal2:index.html.twig', $array);
		return $this -> render('ProyectoPrincipalBundle:User:Login.html.twig', $array);
	}
}
