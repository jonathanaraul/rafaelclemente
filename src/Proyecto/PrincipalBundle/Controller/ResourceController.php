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
//use Proyecto\PrincipalBundle\Entity\CmsArticle;
use Proyecto\PrincipalBundle\Entity\CmsPage;


class ResourceController extends Controller {

	public function listAction($type, Request $request) {

		$config = UtilitiesAPI::getConfig($type, $this);
		$url = $this -> generateUrl('proyecto_principal_resource_list', array('type' => $type));
		$firstArray = UtilitiesAPI::getDefaultContent('RECURSOS', $config['list'], $this);
		$firstArray['type'] = $config['type'];

		$filtros['published'] = array(1 => 'Si', 0 => 'No');

		///////////////////////////////////////////////////////////////////////////////////////////////////
		$data = new CmsResource();
		$form = $this -> createFormBuilder($data) 
		-> add('name', 'text', array('required' => false)) 
		-> add('published', 'choice', array('choices' => $filtros['published'], 'required' => false, )) 
		-> getForm();

		$em = $this -> getDoctrine() -> getEntityManager();

		if ($this -> getRequest() -> isMethod('POST')) {
			$form -> bind($this -> getRequest());

			if ($form -> isValid()) {

				$dql = "SELECT n FROM ProyectoPrincipalBundle:CmsResource n ";
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

				if ($where == false) {
					$dql .= 'WHERE ';
					$where = true;
				} else {
					$dql .= 'AND ';
				}
				$dql .= ' n.type = :type ';

				$query = $em -> createQuery($dql);

				if (!(trim($data -> getName()) == false)) {
					$query -> setParameter('name', '%' . $data -> getName() . '%');
				}
				if (is_numeric($data -> getPublished())) {
					$query -> setParameter('published', $data -> getPublished());
				}
				$query -> setParameter('type', $config['idtype']);

			}
		}
		//////////////////////////////////////////////////////////////////////////////////////////////////
		else {

			$dql = "SELECT n FROM ProyectoPrincipalBundle:CmsResource n WHERE n.type = :type";
			$query = $em -> createQuery($dql);
			$query -> setParameter('type',$config['idtype']);
		}

		$paginator = $this -> get('knp_paginator');
		$pagination = $paginator -> paginate($query, $this -> getRequest() -> query -> get('page', 1), 10);

		$objects = $pagination -> getItems();
		$auxiliar = array();

		for ($i = 0; $i < count($objects); $i++) {
			$auxiliar[$i]['id'] = $objects[$i] -> getId();
			$auxiliar[$i]['name'] = $objects[$i] -> getName();
			$auxiliar[$i]['published'] = $objects[$i] -> getPublished();
			$auxiliar[$i]['dateCreated'] = $objects[$i] -> getDateCreated() -> format('d/m/Y');
			$auxiliar[$i]['path'] = $objects[$i] -> getWebPath();

		}

		$objects = $auxiliar;

		$secondArray = array('pagination' => $pagination, 'filtros' => $filtros, 'objects' => $objects, 'form' => $form -> createView(), 'url' => $url);

		$array = array_merge($firstArray, $secondArray);

		return $this -> render('ProyectoPrincipalBundle:Resource:List.html.twig', $array);
	}

	public function editAction($type, $id, Request $request) {

		$config = UtilitiesAPI::getConfig($type, $this);
		$firstArray = array();
		$firstArray = UtilitiesAPI::getDefaultContent('RECURSOS', $config['edit'], $this);

		$secondArray = array('accion' => 'editar');
		$secondArray['url'] = $this -> generateUrl('proyecto_principal_resource_edit', array('type' => $type, 'id' => $id));
		$secondArray['id'] = $id;
		$array = array_merge($firstArray, $secondArray);
		$array = array_merge($array, $config);

		return ResourceController::normal($array, $request, $this);
	}

	public function createAction($type, Request $request) {

		$config = UtilitiesAPI::getConfig($type, $this);
		$firstArray = array();
		$firstArray = UtilitiesAPI::getDefaultContent('RECURSOS', $config['create'], $this);

		$secondArray = array('accion' => 'nuevo');
		$secondArray['url'] = $this -> generateUrl('proyecto_principal_resource_create', array('type' => $type));
		$array = array_merge($firstArray, $secondArray);
		$array = array_merge($array, $config);

		return ResourceController::normal($array, $request, $this);
	}

	public static function normal($array, Request $request, $class) {

		if ($array['accion'] == 'nuevo')
			$data = new CmsResource();
		else
			$data = $class -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsResource') -> find($array['id']);

		$filtros = array();
		//$filtros['published'] = array(1 => 'Si', 0 => 'No');

		
		$form = $class -> createFormBuilder($data) 
		-> add('name', 'text', array('required' => true)) 
		-> add('file', 'file', array('required' => true)) 
		-> add('published', 'checkbox', array('label' => 'Publicado', 'required' => false, )) 
		-> add('home', 'checkbox', array('label' => 'Pagina Principal', 'required' => false, )) 
		-> getForm();
		

		if ($class -> getRequest() -> isMethod('POST')) {

			$form -> bind($class -> getRequest());
			$em = $class -> getDoctrine() -> getManager();

			if ($form -> isValid()) {
				if ($array['accion'] == 'nuevo') {
					$data -> setRank(0);
					$data -> setSuspended(false);
					$data -> setDateCreated(new \DateTime());
					$data -> setType($array['idtype']);

				} else {
					$data -> setDateUpdated(new \DateTime());
				}
				$data -> setIp($class -> container -> get('request') -> getClientIp());
				$data -> setUser($array['user'] -> getId());

				$em -> persist($data);

				$em -> flush();

				if ($array['accion'] == 'nuevo') {

					$auxiliar = $class -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsResource') -> findByType($array['idtype']);
					if (!$auxiliar)
						$data -> setRank(0);
					else
						$data -> setRank(count($auxiliar) + 1);

					$em -> flush();
				}

				return $class -> redirect($class -> generateUrl('proyecto_principal_resource_list', array('type' => $array['type'])));
			}
		}

		$array['form'] = $form -> createView();

		return $class -> render('ProyectoPrincipalBundle:Resource:New-Edit.html.twig', $array);
	}

	public function translateAction($type, $id, Request $request) {

		$config = UtilitiesAPI::getConfig($type, $this);
		$firstArray = array();
		$firstArray = UtilitiesAPI::getDefaultContent('ARTICULOS', $config['translate'], $this);

		$secondArray = array('accion' => 'editar');
		$secondArray['url'] = $this -> generateUrl('proyecto_principal_article_translate', array('type' => $type, 'id' => $id));
		$secondArray['id'] = $id;

		$data = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsArticleTranslate') -> findOneByArticle($id);
		$object = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsArticle') -> find($id);

		if (!$object) {
			throw $this -> createNotFoundException('El articulo que intenta traducir no existe ');
		}
		//$secondArray = array();
		$secondArray['object'] = $object;

		if (!$data) {
			$secondArray['accion'] = 'nuevo';
			$secondArray['data'] = new CmsArticleTranslate();
		} else {
			$secondArray['accion'] = 'editar';
			$secondArray['data'] = $data;
		}

		//$secondArray['id'] = $id;

		$array = array_merge($firstArray, $secondArray);
		$array = array_merge($array, $config);

		return ArticleController::traduccion($array, $request, $this);
	}

	public function deleteAction() {

		$peticion = $this -> getRequest();
		$doctrine = $this -> getDoctrine();
		$post = $peticion -> request;
		//INICIALIZAR VARIABLES

		$id = $post -> get("id");
		$em = $this -> getDoctrine() -> getManager();

		//Remover original
		$object = $em -> getRepository('ProyectoPrincipalBundle:CmsResource') -> find($id);
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
		$object = $em -> getRepository('ProyectoPrincipalBundle:CmsResource') -> find($id);
		$object -> setPublished($tarea);
		$em -> flush();


		$estado = true;
		$respuesta = new response(json_encode(array('estado' => $estado)));
		$respuesta -> headers -> set('content_type', 'aplication/json');
		return $respuesta;
	}
	public function homeAction() {

		$peticion = $this -> getRequest();
		$doctrine = $this -> getDoctrine();
		$post = $peticion -> request;
		//INICIALIZAR VARIABLES

		$id = $post -> get("id");
		$tarea = intval($post -> get("tarea"));

		$em = $this -> getDoctrine() -> getManager();
		$object = $em -> getRepository('ProyectoPrincipalBundle:CmsResource') -> find($id);
		$object -> setHome($tarea);
		$em -> flush();

		$estado = true;
		$respuesta = new response(json_encode(array('estado' => $estado)));
		$respuesta -> headers -> set('content_type', 'aplication/json');
		return $respuesta;
	}

}
