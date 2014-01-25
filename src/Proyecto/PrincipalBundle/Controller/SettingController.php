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
use Proyecto\PrincipalBundle\Entity\CmsSetting;


class SettingController extends Controller {

	public function listAction(Request $request) {

		$locale = UtilitiesAPI::getLocale($this);
		$type = 'setting';
		$config = UtilitiesAPI::getConfig($type,$this);
		$url = $this -> generateUrl('proyecto_principal_setting_list');
		$firstArray = UtilitiesAPI::getDefaultContent('SETTING', $config['list'], $this);
		$firstArray['type'] = $config['type'];

		///////////////////////////////////////////////////////////////////////////////////////////////////
		$em = $this -> getDoctrine() -> getEntityManager();
		$dql = "SELECT n FROM ProyectoPrincipalBundle:CmsSetting n WHERE n.lang = :lang ";
		$query = $em -> createQuery($dql);
		$query -> setParameter('lang', $locale);
		
		$paginator = $this -> get('knp_paginator');
		$pagination = $paginator -> paginate($query, $this -> getRequest() -> query -> get('page', 1), 15);

		$objects = $pagination -> getItems();
		$auxiliar = array();

		for ($i = 0; $i < count($objects); $i++) {
			$auxiliar[$i]['id'] = $objects[$i] -> getId();
			$auxiliar[$i]['name'] = $objects[$i] -> getName();
			$auxiliar[$i]['value'] = $objects[$i] -> getValue();
			$auxiliar[$i]['dateCreated'] = $objects[$i] -> getDateCreated()->format('d/m/Y H:m');
		}
		$objects = $auxiliar;

		$secondArray = array('pagination' => $pagination, 'objects' => $objects,  'url' => $url);

		$array = array_merge($firstArray, $secondArray);
		
		return $this -> render('ProyectoPrincipalBundle:Setting:List.html.twig', $array);
	}
	
	public function editAction($id, Request $request) {

		$type = 'setting';
		$config = UtilitiesAPI::getConfig($type,$this);
		$firstArray = array();
		$firstArray = UtilitiesAPI::getDefaultContent('SETTING',$config['edit'], $this);

		$secondArray = array('accion' => 'editar');
		$secondArray['url'] = $this -> generateUrl('proyecto_principal_setting_edit', array('id' => $id));
		$secondArray['id'] = $id;
		$array = array_merge($firstArray, $secondArray);
		$array = array_merge($array, $config);

		return SettingController::normal($array, $request, $this);
	}

	public static function normal($array, Request $request, $class) {
			
		$locale = UtilitiesAPI::getLocale($class);

		if ($array['accion'] == 'nuevo')
			$data = new CmsSetting();
		else
			$data = $class -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsSetting') -> find($array['id']);


		$filtros = array();
		
		$form = $class -> createFormBuilder($data) 
		-> add('value', 'hidden', array('data' => '', 'required' => true )) 
		-> getForm();

		if ($class -> getRequest() -> isMethod('POST')) {

			$contenido = $request -> request -> all();
			$contenido = $contenido['page']['content'];
			
			$form -> bind($class -> getRequest());
			$em = $class -> getDoctrine() -> getManager();
			
			
			if ($array['accion'] == 'nuevo') {
				$data -> setLang($locale);
				
			}
			$data -> setValue($contenido);
			$data -> setDateUpdated(new \DateTime());
			$data -> setIp($class -> container -> get('request') -> getClientIp());
			$data -> setUser($array['user'] -> getId());
			
			
			$em -> persist($data);

			$em -> flush();

			return $class -> redirect($class -> generateUrl('proyecto_principal_setting_list'));
				
			//}
			//if ($form -> isValid()) {}
		}

		$array['form'] = $form -> createView();
		
		$array['contenido'] = $array['form'] -> getVars();
		$array['contenido'] = $array['contenido']['value'] -> getValue();
		$array['auxname'] = $data -> getName();


		return $class -> render('ProyectoPrincipalBundle:Setting:New-Edit.html.twig', $array);
	}

	public function deleteAction() {

		$peticion = $this -> getRequest();
		$doctrine = $this -> getDoctrine();
		$post = $peticion -> request;
		//INICIALIZAR VARIABLES

		$id = $post -> get("id");
		$em = $this -> getDoctrine() -> getManager();

		$object = $em -> getRepository('ProyectoPrincipalBundle:CmsLink') -> find($id);
		$em -> remove($object);
		$em -> flush();		

		$estado = true;
		$respuesta = new response(json_encode(array('estado' => $estado)));
		$respuesta -> headers -> set('content_type', 'aplication/json');
		return $respuesta;
	}
	
	public function statusAction() {

		$peticion = $this -> getRequest();
		$doctrine = $this -> getDoctrine();
		$post = $peticion -> request;
		//INICIALIZAR VARIABLES

		$id = $post -> get("id");
		$tarea = intval($post -> get("tarea"));

		$em = $this -> getDoctrine() -> getManager();
		$object = $em -> getRepository('ProyectoPrincipalBundle:CmsLink') -> find($id);
		$object -> setPublished($tarea);
		$em -> flush();
		
		$estado = true;
		$respuesta = new response(json_encode(array('estado' => $estado)));
		$respuesta -> headers -> set('content_type', 'aplication/json');
		return $respuesta;
	}
	public function targetAction() {

		$peticion = $this -> getRequest();
		$doctrine = $this -> getDoctrine();
		$post = $peticion -> request;
		//INICIALIZAR VARIABLES

		$id = $post -> get("id");
		$tarea = intval($post -> get("tarea"));

		$em = $this -> getDoctrine() -> getManager();
		$object = $em -> getRepository('ProyectoPrincipalBundle:CmsLink') -> find($id);
		$object -> setNewWindow($tarea);
		$em -> flush();
		
		$estado = true;
		$respuesta = new response(json_encode(array('estado' => $estado)));
		$respuesta -> headers -> set('content_type', 'aplication/json');
		return $respuesta;
	}

}
