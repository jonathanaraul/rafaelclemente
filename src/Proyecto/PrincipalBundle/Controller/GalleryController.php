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

				$dql = "SELECT n FROM ProyectoPrincipalBundle:CmsArticle n ";
				$where = false;

				if (is_numeric($data -> getCategory())) {

					if ($where == false) {
						$dql .= 'WHERE ';
						$where = true;
					}
					$dql .= ' n.category = :category ';

				}
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

				if ($where == false) {
					$dql .= 'WHERE ';
					$where = true;
					} 
				else{
					$dql .= 'AND ';
					}
				$dql .= ' n.lang = :lang ';

				$query = $em -> createQuery($dql);

				if (is_numeric($data -> getCategory())) {
					$query -> setParameter('category', $data -> getCategory());
				}
				if (!(trim($data -> getName()) == false)) {
					$query -> setParameter('name', '%' . $data -> getName() . '%');
				}
				if (is_numeric($data -> getPublished())) {
					$query -> setParameter('published', $data -> getPublished());
				}
				$query -> setParameter('type', $config['idtype']);
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
			if(is_null($auxiliar[$i]['page'])){
				$auxiliar[$i]['page'] ="-";
			}
			else if($auxiliar[$i]['page']==0){
				$auxiliar[$i]['page'] ="Inicio";
			} 
			$auxiliar[$i]['article'] = $objects[$i] -> getArticle();
			if(is_null($auxiliar[$i]['article'])){
				$auxiliar[$i]['article'] ="-";
			}

			$auxiliar[$i]['published'] = $objects[$i] -> getPublished();
			$auxiliar[$i]['dateCreated'] = $objects[$i] -> getDateCreated()->format('d/m/Y');
		}
		$objects = $auxiliar;

		$secondArray = array('pagination' => $pagination, 'filtros' => $filtros, 'objects' => $objects, 'form' => $form -> createView(), 'url' => $url);

		$array = array_merge($firstArray, $secondArray);
		return $this -> render('ProyectoPrincipalBundle:Gallery:List.html.twig', $array);
	}
	
	public function editAction($type,$id, Request $request) {

		$config = UtilitiesAPI::getConfig($type,$this);
		$firstArray = array();
		$firstArray = UtilitiesAPI::getDefaultContent('ARTICULOS',$config['edit'], $this);

		$secondArray = array('accion' => 'editar');
		$secondArray['url'] = $this -> generateUrl('proyecto_principal_article_edit', array('type'=>$type,'id' => $id));
		$secondArray['id'] = $id;
		$secondArray['fechasprevias'] = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsDate') -> findByArticle($id);

		$array = array_merge($firstArray, $secondArray);
		$array = array_merge($array, $config);

		return ArticleController::normal($array, $request, $this);
	}
	public function createAction($type,Request $request) {
		
		$config = UtilitiesAPI::getConfig($type,$this);
		$firstArray = array();
		$firstArray = UtilitiesAPI::getDefaultContent('ARTICULOS',$config['create'], $this);

		$secondArray = array('accion' => 'nuevo');	
		$secondArray['url'] = $this -> generateUrl('proyecto_principal_article_create',array('type' => $type));
		$array = array_merge($firstArray, $secondArray);
		$array = array_merge($array, $config);

		return ArticleController::normal($array, $request, $this);
	}
	public static function normal($array, Request $request, $class) {
			
		$locale = UtilitiesAPI::getLocale($class);

		if ($array['accion'] == 'nuevo')
			$data = new CmsArticle();
		else
			$data = $class -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsArticle') -> find($array['id']);

		$objects = $class -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsPage') -> findAll();
		$resource = $class -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsResource') -> findByType(3);
		

		$filtros['published'] = array(1 => 'Si', 0 => 'No');
		$filtros['resource'] = UtilitiesAPI::getFilterData($resource, $class);
		

		$validaciones = array(true,true);
		if($array['type']=='news') 	$validaciones = array(false,false);
		
		$form = $class -> createFormBuilder($data) 
		-> add('name', 'text', array('required' => true)) 
		-> add('place', 'text', array('required' => true)) 
		-> add('keywords', 'text', array('required' => true)) 
		-> add('content', 'hidden', array('data' => '', )) 
		-> add('resource', 'choice', array('choices' => $filtros['resource'], 'required' => true, )) 
		-> add('published', 'checkbox', array('label' => 'Publicado', 'required' => false, )) 
		-> getForm();
		
		if ($class -> getRequest() -> isMethod('POST')) {

			$contenido = $request -> request -> all();

			$em = $class -> getDoctrine() -> getManager();
			$fechas = null;
			
			//$fechas = $contenido['fechas'];
			
			
			$contenido = $contenido['page']['content'];

			$form -> bind($class -> getRequest());
			

			if ($array['accion'] == 'nuevo') {
				$data -> setLang($locale);
				$data -> setSuspended(0);
				$data -> setDateCreated(new \DateTime());
				$data -> setRank(0);

				$data -> setDate(new \DateTime());
				
			} else {
				$data -> setDateUpdated(new \DateTime());
			}
			$data -> setContent($contenido);
			$data -> setIp($class -> container -> get('request') -> getClientIp());
			$data -> setUser($array['user'] -> getId());
			$data -> setFriendlyName($data -> getName());

			if ($array['accion'] == 'nuevo')
				$em -> persist($data);

			$em -> flush();
			/*
			if($array['type'] != 'news'){
				
				$fechasBorrar= $class -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsDate') -> findByArticle($data->getId());
				for ($i=0; $i < count($fechasBorrar); $i++) {
					$em -> remove($fechasBorrar[$i]);
					$em -> flush();
					}

			foreach ($fechas as $key => $value) {

				$fecha = new CmsDate();
				$fecha->setArticle($data);
				$fecha->setDate(UtilitiesAPI::convertirFechaNormal($value,$class));
				$em -> persist($fecha);
				$em -> flush();
			}
			}*/
			return $class -> redirect($class -> generateUrl('proyecto_principal_article_list',array('type' => $array['type'])));
			//if ($form -> isValid()) {}
		}

		$array['form'] = $form -> createView();
		$array['resource'] = $resource;
		$array['contenido'] = $array['form'] -> getVars();
		$array['contenido'] = $array['contenido']['value'] -> getContent();

		return $class -> render('ProyectoPrincipalBundle:Article:New-Edit.html.twig', $array);
	}
	public function deleteAction() {

		$peticion = $this -> getRequest();
		$doctrine = $this -> getDoctrine();
		$post = $peticion -> request;
		//INICIALIZAR VARIABLES

		$id = $post -> get("id");
		$em = $this -> getDoctrine() -> getManager();
		
		//Remover original
		$object = $em -> getRepository('ProyectoPrincipalBundle:CmsArticle') -> find($id);
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
		$object = $em -> getRepository('ProyectoPrincipalBundle:CmsArticle') -> find($id);
		$object -> setPublished($tarea);
		$em -> flush();
		
		$estado = true;
		$respuesta = new response(json_encode(array('estado' => $estado)));
		$respuesta -> headers -> set('content_type', 'aplication/json');
		return $respuesta;
	}
	public static function insertaFechas($class)
	{
			echo "llego a la funcion";
			$articles = array( 43 =>14, 44=>16 );//Id articulo => hora funcion
			
			$fecha = new \DateTime("now");
			var_dump($fecha);
			$em = $class -> getDoctrine() -> getEntityManager();	
			
			
			for ($i=0; $i < 30 ; $i++) {
				foreach ($articles as $key => $value) {
					
					$fecha->setTime ( $value, 0, 0 );
					
					$date = new CmsDate();
					$date->setArticle($class -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsArticle') -> find($key));
					$date->setDate($fecha);
					$em -> persist($date);
					$em -> flush();

					$fecha->add(new \DateInterval('PT3H'));

					$date = new CmsDate();
					$date->setArticle($class -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsArticle') -> find($key));
					$date->setDate($fecha);
					$em -> persist($date);
					$em -> flush();

				}
			$fecha->add(new \DateInterval('P1D'));
			}
			//echo'final de funcion';

			foreach ($articles as $key => $value) {
				$dates = $class -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsDate') -> findByArticle($key);
				echo "</br><hr>\n Para el articulo de Id ".$key.' las fechas son:</br>\n';
				var_dump($dates);
			}
			
			
		exit;
	}

}
