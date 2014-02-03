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
use Proyecto\PrincipalBundle\Entity\CmsResource;
use Proyecto\PrincipalBundle\Entity\CmsTextResource;
use Proyecto\PrincipalBundle\Entity\CmsPage;


class TextResourceController extends Controller {

	public function listAction(Request $request) {

		//echo'estamos en textos asociados';exit;
		$locale = UtilitiesAPI::getLocale($this);
		$config = UtilitiesAPI::getConfig('text-resource',$this);
		$url = $this -> generateUrl('proyecto_principal_text_resource_list');
		$firstArray = UtilitiesAPI::getDefaultContent('RECURSOS', $config['list'], $this);
		$firstArray['type'] = $config['type'];

		$filtros['published'] = array(1 => 'Si', 0 => 'No');
		///////////////////////////////////////////////////////////////////////////////////////////////////
		$data = new CmsTextResource();
		$form = $this -> createFormBuilder($data) 
		-> add('name', 'text', array('required' => false))
		-> add('published', 'choice', array('choices' => $filtros['published'], 'required' => false, )) 
		-> getForm();

		$em = $this -> getDoctrine() -> getEntityManager();

		if ($this -> getRequest() -> isMethod('POST')) {
			$form -> bind($this -> getRequest());

			if ($form -> isValid()) {

				$dql = "SELECT n FROM ProyectoPrincipalBundle:CmsTextResource n ";
				$where = false;

				if (!(trim($data -> getName()) == false)) {

					if ($where == false) {
						$dql .= 'WHERE ';
						$where = true;
					} else {
						$dql .= 'AND ';
					}

					$dql .= " n.name like :name ";

				}
				if (is_numeric($data -> getPublished())) {

					if ($where == false) {
						$dql .= 'WHERE ';
						$where = true;
					} else {
						$dql .= 'AND ';
					}
					$dql .= ' n.published = :published ';
				}
				/*
				if ($where == false) {
						$dql .= 'WHERE ';
						$where = true;
				} else {
						$dql .= 'AND ';
				}
				$dql .= ' n.type = :type ';
				*/
				if ($where == false) {
					$dql .= 'WHERE ';
					$where = true;
					} 
				else{
					$dql .= 'AND ';
					}
				$dql .= ' n.lang = :lang ';

				$query = $em -> createQuery($dql);

				if (!(trim($data -> getName()) == false)) {
					$query -> setParameter('name', '%' . $data -> getName() . '%');
				}
				if (is_numeric($data -> getPublished())) {
					$query -> setParameter('published', $data -> getPublished());
				}
				//$query -> setParameter('type', $config['idtype']);
				$query -> setParameter('lang', $locale);
			}
		}
		//////////////////////////////////////////////////////////////////////////////////////////////////
		else {
			$dql = "SELECT n FROM ProyectoPrincipalBundle:CmsTextResource n WHERE n.lang = :lang ";
			$query = $em -> createQuery($dql);
			$query -> setParameter('lang', $locale);
		}

		$paginator = $this -> get('knp_paginator');
		$pagination = $paginator -> paginate($query, $this -> getRequest() -> query -> get('page', 1), 10);

		$objects = $pagination -> getItems();
		$auxiliar = array();

		for ($i = 0; $i < count($objects); $i++) {
			$auxiliar[$i]['id'] = $objects[$i] -> getId();
			$auxiliar[$i]['resource'] = '0';
			if($objects[$i] -> getResource() != 0){
				$helper = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsResource') -> find($objects[$i] -> getResource());
				if($helper!= NULL){
					$auxiliar[$i]['resource'] = $helper  -> getWebPath();
				}
			}
			$auxiliar[$i]['name'] = $objects[$i] -> getName();
			$auxiliar[$i]['published'] = $objects[$i] -> getPublished();
		}
		$objects = $auxiliar;

		$secondArray = array('pagination' => $pagination, 'filtros' => $filtros, 'objects' => $objects, 'form' => $form -> createView(), 'url' => $url);

		$array = array_merge($firstArray, $secondArray);
		return $this -> render('ProyectoPrincipalBundle:TextResource:List.html.twig', $array);
	}

	public function createAction(Request $request) {
		$config = UtilitiesAPI::getConfig('text-resource',$this);
		$firstArray = UtilitiesAPI::getDefaultContent('RECURSOS', $config['create'], $this);

		$secondArray = array('accion' => 'nuevo');
		$secondArray['url'] = $this -> generateUrl('proyecto_principal_text_resource_create');
		$secondArray['data'] = new CmsTextResource();

		$array = array_merge($firstArray, $secondArray);
		return TextResourceController::procesar($array, $request, $this);
	}

	public function editAction($id, Request $request) {

		$config = UtilitiesAPI::getConfig('text-resource',$this);
		$firstArray = UtilitiesAPI::getDefaultContent('RECURSOS', $config['edit'], $this);

		
		$firstArray = UtilitiesAPI::getDefaultContent('PAGINAS', 'Editar Información', $this);
		$secondArray = array('accion' => 'edicion');
		$secondArray['url'] = $this -> generateUrl('proyecto_principal_text_resource_edit', array('id' => $id));
		$secondArray['id'] = $id;

		$secondArray['data'] = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsTextResource') -> find($id);
		if (!$secondArray['data']) {
			throw $this -> createNotFoundException('El texto asociado que intenta editar no existe');
		}
		
		$array = array_merge($firstArray, $secondArray);
		return TextResourceController::procesar($array, $request, $this);
	}
	public static function procesar($array, Request $request, $class) {
			
		
		$locale = UtilitiesAPI::getLocale($class);
		$data = $array['data'];
		
		$array['resource']  = $class -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsResource') -> findByType(3);
				
		$filtros = array();
		$filtros['published'] = array(1 => 'Si', 0 => 'No');
		$filtros['resource'] = UtilitiesAPI::getFilterData($array['resource'],$class);

		$em = $class -> getDoctrine() -> getEntityManager();	

		$form = $class -> createFormBuilder($data) -> add('name', 'text', array('required' => true))
		 -> add('name', 'text', array('required' => true)) 
		 -> add('subtitle', 'text', array('required' => false)) 
		 -> add('year', 'text', array('required' => false)) 
		 -> add('content', 'hidden', array('data' => '', ))
		 -> add('resource', 'choice', array('choices' => $filtros['resource'], 'required' => true, )) 
		 -> add('published', 'checkbox', array('label' => 'Publicado', 'required' => false, )) 
		 -> getForm();

		
		if ($class -> getRequest() -> isMethod('POST')) {

			$contenido = $request -> request -> all();
			$contenido = $contenido['page']['content'];

			$form -> bind($class -> getRequest());
			$em = $class -> getDoctrine() -> getManager();
			
			if ($array['accion'] == 'nuevo') {
				$data -> setLang($locale);
			} 
			
			$data -> setContent($contenido);
			$data -> setIp($class -> container -> get('request') -> getClientIp());
			$data -> setUser($array['user'] -> getId());

			if ($array['accion'] == 'nuevo')
				$em -> persist($data);

			$em -> flush();
			
			return $class -> redirect($class -> generateUrl('proyecto_principal_text_resource_list'));
			//if ($form -> isValid()) {}
		}
		$array['form'] = $form -> createView();
		$array['contenido'] = $array['form'] -> getVars();
		$array['contenido'] = $array['contenido']['value'] -> getContent();


		return $class -> render('ProyectoPrincipalBundle:TextResource:New-Edit.html.twig', $array);
	}


	public function deleteAction() {

		$peticion = $this -> getRequest();
		$doctrine = $this -> getDoctrine();
		$post = $peticion -> request;
		//INICIALIZAR VARIABLES

		$id = $post -> get("id");
		$em = $this -> getDoctrine() -> getManager();
		$object = $em -> getRepository('ProyectoPrincipalBundle:CmsTextResource') -> find($id);
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
		$object = $em -> getRepository('ProyectoPrincipalBundle:CmsTextResource') -> find($id);
		$object -> setPublished($tarea);
		$em -> flush();

		$estado = true;
		$respuesta = new response(json_encode(array('estado' => $estado)));
		$respuesta -> headers -> set('content_type', 'aplication/json');
		return $respuesta;
	}
}