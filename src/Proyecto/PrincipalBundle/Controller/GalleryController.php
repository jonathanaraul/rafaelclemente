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
use Proyecto\PrincipalBundle\Entity\CmsGallery;
use Proyecto\PrincipalBundle\Entity\CmsGalleryResource;


class GalleryController extends Controller {

	public function listAction(Request $request) {

		$locale = UtilitiesAPI::getLocale($this);
		$config = UtilitiesAPI::getConfig('gallery',$this);
		$url = $this -> generateUrl('proyecto_principal_gallery_list');
		$firstArray = UtilitiesAPI::getDefaultContent('RECURSOS', $config['list'], $this);
		$firstArray['type'] = $config['type'];

		$filtros['published'] = array(1 => 'Si', 0 => 'No');
		///////////////////////////////////////////////////////////////////////////////////////////////////
		$data = new CmsGallery();
		$form = $this -> createFormBuilder($data) 
		-> add('name', 'text', array('required' => false))
		-> add('published', 'choice', array('choices' => $filtros['published'], 'required' => false, )) 
		-> getForm();

		$em = $this -> getDoctrine() -> getEntityManager();

		if ($this -> getRequest() -> isMethod('POST')) {
			$form -> bind($this -> getRequest());

			if ($form -> isValid()) {

				$dql = "SELECT n FROM ProyectoPrincipalBundle:CmsGallery n ";
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
			$dql = "SELECT n FROM ProyectoPrincipalBundle:CmsGallery n WHERE n.lang = :lang ";
			$query = $em -> createQuery($dql);
			$query -> setParameter('lang', $locale);
		}

		$paginator = $this -> get('knp_paginator');
		$pagination = $paginator -> paginate($query, $this -> getRequest() -> query -> get('page', 1), 10);

		$objects = $pagination -> getItems();
		$auxiliar = array();

		for ($i = 0; $i < count($objects); $i++) {
			$auxiliar[$i]['id'] = $objects[$i] -> getId();
			$auxiliar[$i]['name'] = $objects[$i] -> getName();
			$auxiliar[$i]['page'] = $objects[$i] -> getPage();

			if((is_null($auxiliar[$i]['page']))==false && $auxiliar[$i]['page']==0) {
				$auxiliar[$i]['page'] = "Inicio";
			}
				
			else $auxiliar[$i]['page'] = UtilitiesAPI::getSingleAttribute('CmsPage','name',$auxiliar[$i]['page'],$this);
			
			$auxiliar[$i]['article'] = UtilitiesAPI::getSingleAttribute('CmsArticle','name',$objects[$i] -> getArticle(),$this);
			
			$auxiliar[$i]['published'] = $objects[$i] -> getPublished();
			$auxiliar[$i]['dateCreated'] = $objects[$i] -> getDateCreated()->format('d/m/Y');
		}
		$objects = $auxiliar;

		$secondArray = array('pagination' => $pagination, 'filtros' => $filtros, 'objects' => $objects, 'form' => $form -> createView(), 'url' => $url);

		$array = array_merge($firstArray, $secondArray);
		return $this -> render('ProyectoPrincipalBundle:Gallery:List.html.twig', $array);
	}
	
	public function editAction($id, Request $request) {

		$config = UtilitiesAPI::getConfig('gallery',$this);
		$firstArray = array();
		$firstArray = UtilitiesAPI::getDefaultContent('RECURSOS',$config['edit'], $this);

		$secondArray = array('accion' => 'editar');
		$secondArray['url'] = $this -> generateUrl('proyecto_principal_gallery_edit', array('id' => $id));
		$secondArray['id'] = $id;
		
		$array = array_merge($firstArray, $secondArray);
		$array = array_merge($array, $config);

		return GalleryController::normal($array, $request, $this);
	}
	public function createAction(Request $request) {
		
		$config = UtilitiesAPI::getConfig('gallery',$this);
		$firstArray = array();
		$firstArray = UtilitiesAPI::getDefaultContent('RECURSOS',$config['create'], $this);

		$secondArray = array('accion' => 'nuevo');	
		$secondArray['url'] = $this -> generateUrl('proyecto_principal_gallery_create');
		
		$array = array_merge($firstArray, $secondArray);
		$array = array_merge($array, $config);

		return GalleryController::normal($array, $request, $this);
	}
	public static function normal($array, Request $request, $class) {
			
		$locale = UtilitiesAPI::getLocale($class);

		if ($array['accion'] == 'nuevo')
			$data = new CmsGallery();
		else{
			$data = $class -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsGallery') -> find($array['id']);
			$array['galleryResources'] = UtilitiesAPI::getGalleryResources($array['id'],$class);
		}

		$filtros['published'] = array(1 => 'Si', 0 => 'No');
		$filtros['page'] = UtilitiesAPI::getDataByEntity('CmsPage',false,$class);
		$filtros['article'] = UtilitiesAPI::getDataByEntity('CmsArticle',false,$class);
		$array['resources'] = UtilitiesAPI::getDataByEntityWithoutLang('CmsResource',$class);
		$array['resources'] = UtilitiesAPI::devuelveRecursosExistentes($array['resources'],$class);

		$filtros['published'] = array(1 => 'Si', 0 => 'No');
		
		$form = $class -> createFormBuilder($data) 
		-> add('name', 'text', array('required' => true)) 
		-> add('subtitle', 'text', array('required' => true)) 
		-> add('keywords', 'text', array('required' => true)) 
		-> add('content', 'hidden', array('data' => '', )) 
		-> add('page', 'choice', array('choices' => $filtros['page'], 'required' => false, )) 
		-> add('article', 'choice', array('choices' => $filtros['article'], 'required' => false, )) 
		-> add('published', 'checkbox', array('label' => 'Publicado', 'required' => false, )) 
		-> getForm();
		
		if ($class -> getRequest() -> isMethod('POST')) {

			$em = $class -> getDoctrine() -> getManager();

			$contenido = $request -> request -> all();			
			$numeracion = $contenido['numeracion'];
			
			$contenido = $contenido['page']['content'];

			$form -> bind($class -> getRequest());
			

			if ($array['accion'] == 'nuevo') {
				$data -> setLang($locale);
				$data -> setSuspended(0);
				$data -> setDateCreated(new \DateTime());
				
			} else {
				$data -> setDateUpdated(new \DateTime());
				if($data->getId()==1){
					$data->setPage(0);
				}
			}
			$data -> setType(3);
			$data -> setContent($contenido);
			$data -> setIp($class -> container -> get('request') -> getClientIp());
			$data -> setUser($array['user'] -> getId());
			$data -> setFriendlyName($data -> getName());

			if ($array['accion'] == 'nuevo')
				$em -> persist($data);

			$em -> flush();

			$elementosPrevios = $class -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsGalleryResource') -> findByGallery($data->getId());
			
			for ($i=0; $i < count($elementosPrevios); $i++) {
				$em -> remove($elementosPrevios[$i]);
				$em -> flush();
			}


			$arregloAuxiliar =explode(",", $numeracion);
			
			for ($i=0; $i < count($arregloAuxiliar); $i++) {

				$element = new CmsGalleryResource();
				$element->setGallery($data->getId());
				$element->setResource($arregloAuxiliar[$i]);
				$element->setRank($i+1);
				$element->setIp($class -> container -> get('request') -> getClientIp());
				$element->setUser($array['user'] -> getId());
				$em -> persist($element);
				$em -> flush();
			}
			
			return $class -> redirect($class -> generateUrl('proyecto_principal_gallery_list'));
			//if ($form -> isValid()) {}
		}

		$array['form'] = $form -> createView();
		//$array['resource'] = $resource;
		$array['contenido'] = $array['form'] -> getVars();
		$array['contenido'] = $array['contenido']['value'] -> getContent();


		return $class -> render('ProyectoPrincipalBundle:Gallery:New-Edit.html.twig', $array);
	}
	public function deleteAction() {

		$peticion = $this -> getRequest();
		$doctrine = $this -> getDoctrine();
		$post = $peticion -> request;
		//INICIALIZAR VARIABLES

		$id = $post -> get("id");
		$em = $this -> getDoctrine() -> getManager();
		
		//Remover original
		$object = $em -> getRepository('ProyectoPrincipalBundle:CmsGallery') -> find($id);
		if((is_null($object))==false){
			$em -> remove($object);
			$em -> flush();
		}

		$objects = $em -> getRepository('ProyectoPrincipalBundle:CmsGalleryResource') -> findByGallery($id);
		for ($i=0; $i < count($objects) ; $i++) { 
			$em -> remove($objects[$i]);
			$em -> flush();
		}

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
		$object = $em -> getRepository('ProyectoPrincipalBundle:CmsArticle') -> find($id);
		$object -> setPublished($tarea);
		$em -> flush();
		
		$estado = true;
		$respuesta = new response(json_encode(array('estado' => $estado)));
		$respuesta -> headers -> set('content_type', 'aplication/json');
		return $respuesta;
	}
	
}
