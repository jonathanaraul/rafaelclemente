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
use Proyecto\PrincipalBundle\Entity\CmsPage;
use Proyecto\PrincipalBundle\Entity\CmsPageTranslate;
use Proyecto\PrincipalBundle\Entity\CmsBackground;
use Proyecto\PrincipalBundle\Entity\CmsTheme;
use Proyecto\PrincipalBundle\Entity\CmsMedia;

class PageController extends Controller {

	public function listAction(Request $request) {
		
		$config = UtilitiesAPI::getConfig('pages',$this);
		$url = $this -> generateUrl('proyecto_principal_page_list');
		$firstArray = UtilitiesAPI::getDefaultContent('PAGINAS', 'Mostrar Información', $this);

		$locale = UtilitiesAPI::getLocale($this);
		$form = null;		
		$filtros = null;
	
		$objects = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsPage') -> findAll();
		$themes = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsTheme') -> findAll();
		$filtros['theme'] = array();
		$filtros['parentPage'] = array();
		$filtros['published'] = array(1 => 'Si', 0 => 'No');

		$filtros['theme']= UtilitiesAPI::getFilter('CmsTheme',$this);
		$filtros['parentPage']= UtilitiesAPI::getFilter('CmsPage',$this);


		$data = new CmsPage();
		$form = $this -> createFormBuilder($data) 
		-> add('name', 'text', array('required' => false)) 
		-> add('special','choice', array('choices' => $filtros['published'], 'required' => false, ))
		-> add('theme', 'choice', array('choices' => $filtros['theme'], 'required' => false, )) 
		-> add('published', 'choice', array('choices' => $filtros['published'], 'required' => false, ))
		-> getForm();
		
		$em = $this -> getDoctrine() -> getEntityManager();
				
		if ($this -> getRequest() -> isMethod('POST')) {
			$form -> bind($this -> getRequest());

			if ($form -> isValid()) {

				$dql = "SELECT n FROM ProyectoPrincipalBundle:CmsPage n ";
				$where = false;

				if (is_numeric($data -> getSpecial()))  {

					if ($where == false) {
						$dql .= 'WHERE ';
						$where = true;
					}
					$dql .= ' n.special = :special ';

				}
				if (is_numeric($data -> getTheme())) {

					if ($where == false) {
						$dql .= 'WHERE ';
						$where = true;
					} else {
						$dql .= 'AND ';
					}
					$dql .= ' n.theme = :theme ';

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
					} 
				else{
					$dql .= 'AND ';
					}
				$dql .= ' n.lang = :lang ';
		
				$query = $em -> createQuery($dql);

				if (is_numeric ($data -> getSpecial())) {
					$query -> setParameter('special', $data -> getSpecial());
				}
				if (is_numeric ($data -> getTheme()) ) {
					$query -> setParameter('theme', $data -> getTheme());
				}
				if (!(trim($data -> getName()) == false)) {
					$query -> setParameter('name', '%' . $data -> getName() . '%');
				}
				if (is_numeric ($data -> getPublished())) {
					$query -> setParameter('published', $data -> getPublished());
				}
				
				$query -> setParameter('lang', $locale);

			}
		}
		//////////////////////////////////////////////////////////////////////////////////////////////////
		else {
			$dql = "SELECT n FROM ProyectoPrincipalBundle:CmsPage n ";
			$dql .= 'WHERE n.lang = :lang ';
			$query = $em -> createQuery($dql);
			$query -> setParameter('lang', $locale);
		}

		$paginator = $this -> get('knp_paginator');
		$pagination = $paginator -> paginate($query, $this -> getRequest() -> query -> get('page', 1), 10);

		$objects = $pagination -> getItems();
		$auxiliar = array();

		for ($i = 0; $i < count($objects); $i++) {
			$auxiliar[$i]['id'] = $objects[$i] -> getId();
			$auxiliar[$i]['spacer'] = $objects[$i] -> getSpacer();
			$auxiliar[$i]['special'] = $objects[$i] -> getSpecial();
			$auxiliar[$i]['friendlyName'] = $objects[$i] -> getFriendlyName();
			$auxiliar[$i]['name'] = $objects[$i] -> getName();
			$auxiliar[$i]['published'] = $objects[$i] -> getPublished();
			$auxiliar[$i]['background'] = '-';

			if($objects[$i] -> getBackground() != 0){
				$helper = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsResource') -> find($objects[$i] -> getBackground());
				if($helper!= NULL){
					$auxiliar[$i]['background'] = $helper  -> getWebPath();
				}
			}

			$auxiliar[$i]['theme'] = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsTheme') -> find($objects[$i] -> getTheme()) -> getColor();
			//$auxiliar[$i]['media'] = ($objects[$i] -> getMedia() == 0) ? '0' : '' . $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsResource') -> find($objects[$i] -> getMedia()) -> getWebPath();
			$auxiliar[$i]['media'] = '0';
			if($objects[$i] -> getMedia() != 0){
				$helper = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsResource') -> find($objects[$i] -> getMedia());
				if($helper!= NULL){
					$auxiliar[$i]['media'] = $helper  -> getWebPath();
				}
			}

		}
		$objects = $auxiliar;
		$secondArray = array('pagination' => $pagination, 'filtros' => $filtros, 'objects' => $objects, 'url' => $url);
		$secondArray['form'] =  $form -> createView();
		
		$array = array_merge($firstArray, $secondArray);
		return $this -> render('ProyectoPrincipalBundle:Page:List.html.twig', $array);
	}


	public function createAction(Request $request) {
		$firstArray = UtilitiesAPI::getDefaultContent('PAGINAS', 'Nueva Pagina', $this);
		$secondArray = array('accion' => 'nuevo');
		$secondArray['url'] = $this -> generateUrl('proyecto_principal_page_create');
		$secondArray['data'] = new CmsPage();

		$array = array_merge($firstArray, $secondArray);
		return PageController::procesar($array, $request, $this);
	}

	public function editAction($id, Request $request) {

		$firstArray = UtilitiesAPI::getDefaultContent('PAGINAS', 'Editar Información', $this);
		$secondArray = array('accion' => 'edicion');
		$secondArray['url'] = $this -> generateUrl('proyecto_principal_page_edit', array('id' => $id));
		$secondArray['id'] = $id;

		$secondArray['data'] = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsPage') -> find($id);
		if (!$secondArray['data']) {
			throw $this -> createNotFoundException('La pagina que intenta e no existe ');
		}
		
		$array = array_merge($firstArray, $secondArray);
		return PageController::procesar($array, $request, $this);
	}

	public static function procesar($array, Request $request, $class) {
			
		$locale = UtilitiesAPI::getLocale($class);
		$data = $array['data'];
		
		$array['themes'] = $class -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsTheme') -> findAll();
		$array['media']  = $class -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsResource') -> findByType(3);

		$filtros = array();
		$filtros['published'] = array(1 => 'Si', 0 => 'No');
		$filtros['theme'] = UtilitiesAPI::getFilterData($array['themes'],$class);
		$filtros['parentPage'] = UtilitiesAPI::getFilter('CmsPage',$class);
		$filtros['media'] = UtilitiesAPI::getFilterData($array['media'],$class);
		
		$em = $class -> getDoctrine() -> getEntityManager();	
		
		$dql = "SELECT n.id, n.name
		        FROM ProyectoPrincipalBundle:CmsResource n 
		        WHERE n.path not like :path and
		              n.type = :type
		        ORDER by n.name ASC ";
	
		$query = $em -> createQuery($dql);
		$query -> setParameter('path', '%nodisponible.jpg%');
		$query -> setParameter('type', 4);
		
		$filtros['background'] = $query -> getResult();
		$helper = array();
		for ($i=0; $i < count($filtros['background']) ; $i++) { 
			$helper[$filtros['background'][$i]['id']] = $filtros['background'][$i]['name'];
		}
		$filtros['background'] = $helper;

		$form = $class -> createFormBuilder($data) -> add('name', 'text', array('required' => true))
		 -> add('title', 'text', array('required' => true)) 
		 -> add('descriptionMeta', 'text', array('required' => true)) 
		 -> add('keywords', 'text', array('required' => true)) 
		 -> add('content', 'hidden', array('data' => '', ))
		 -> add('upperText', 'text', array('required' => true)) 
		 -> add('lowerText', 'text', array('required' => true))
		 -> add('file', 'file', array('required' => false)) 
		 -> add('parentPage', 'choice', array('choices' => $filtros['parentPage'], 'required' => false, )) 
		 -> add('theme', 'choice', array('choices' => $filtros['theme'], 'required' => true, )) 
		 -> add('media', 'choice', array('choices' => $filtros['media'], 'required' => true, )) 
		 -> add('background', 'choice', array('choices' => $filtros['background'], 'required' => true, )) 
		 -> add('published', 'checkbox', array('label' => 'Publicado', 'required' => false, )) 
		 -> getForm();

		if ($class -> getRequest() -> isMethod('POST')) {

			$contenido = $request -> request -> all();
			$contenido = $contenido['page']['content'];

			$form -> bind($class -> getRequest());
			$em = $class -> getDoctrine() -> getManager();

			if ($array['accion'] == 'nuevo') {
				$data -> setSpecial(0);
				$data -> setLang($locale);
				$data -> setRank(UtilitiesAPI::getRank($locale, $class));
				$data -> setSuspended(0);
				$data -> setSpacer(0);
				$data -> setTemplate(0);
				$data -> setDescription('');
				$data -> setDateCreated(new \DateTime());
				$data -> setFriendlyName(UtilitiesAPI::getFriendlyName($data->getTitle(),$class));
			} else {
				$data -> setDateUpdated(new \DateTime());
			}
			
			//$data -> setRemove(1);
			$data -> setContent($contenido);
			$data -> setIp($class -> container -> get('request') -> getClientIp());
			$data -> setUser($array['user'] -> getId());
			

			if ($array['accion'] == 'nuevo')
				$em -> persist($data);

			$em -> flush();
			
			return $class -> redirect($class -> generateUrl('proyecto_principal_page_list'));
			//if ($form -> isValid()) {}
		}
		$array['form'] = $form -> createView();
		$array['contenido'] = $array['form'] -> getVars();
		$array['contenido'] = $array['contenido']['value'] -> getContent();

		return $class -> render('ProyectoPrincipalBundle:Page:New-Edit.html.twig', $array);
	}

	public function rankAction() {
		$firstArray = UtilitiesAPI::getDefaultContent('PAGINAS', 'Mostrar Información', $this);

		$em = $this -> getDoctrine() -> getManager();
		$dql = "SELECT n FROM ProyectoPrincipalBundle:CmsPage n WHERE n.lang = :lang ORDER BY n.rank ASC";
		
		$query = $em -> createQuery($dql);
		$query -> setParameter('lang', UtilitiesAPI::getLocale($this));
		$objects = $query -> getResult();

		$secondArray = array('objects' => $objects);
		$array = array_merge($firstArray, $secondArray);

		return $this -> render('ProyectoPrincipalBundle:Page:Rank.html.twig', $array);
	}

	public function rankPostAction() {

		$peticion = $this -> getRequest();
		$doctrine = $this -> getDoctrine();
		$post = $peticion -> request;
		//INICIALIZAR VARIABLES
		$order = $post -> get("order");
		$em = $this -> getDoctrine() -> getManager();
		for ($i = 0; $i < count($order); $i++) {

			$id = intval($order[$i]);
			$object = $em -> getRepository('ProyectoPrincipalBundle:CmsPage') -> find($id);
			$object -> setRank($i);
			$em -> flush();

		}

		$estado = true;
		$respuesta = new response(json_encode(array('estado' => $estado)));
		$respuesta -> headers -> set('content_type', 'aplication/json');
		return $respuesta;
	}

	public function deleteAction() {

		$peticion = $this -> getRequest();
		$doctrine = $this -> getDoctrine();
		$post = $peticion -> request;
		//INICIALIZAR VARIABLES

		$id = $post -> get("id");
		$em = $this -> getDoctrine() -> getManager();
		$object = $em -> getRepository('ProyectoPrincipalBundle:CmsPage') -> find($id);
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
		$object = $em -> getRepository('ProyectoPrincipalBundle:CmsPage') -> find($id);
		$object -> setPublished($tarea);
		$em -> flush();

		$estado = true;
		$respuesta = new response(json_encode(array('estado' => $estado)));
		$respuesta -> headers -> set('content_type', 'aplication/json');
		return $respuesta;
	}

}
