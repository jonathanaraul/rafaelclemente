<?php

namespace Proyecto\FrontBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Proyecto\PrincipalBundle\Entity\Data;
use Proyecto\PrincipalBundle\Entity\Categoria;
use Proyecto\PrincipalBundle\Entity\Entrada;
use Proyecto\PrincipalBundle\Entity\Usuario;
use Proyecto\PrincipalBundle\Entity\CmsReservation;
use Proyecto\PrincipalBundle\Entity\CmsPage;


class ListadosController extends Controller {

	const tamanio = 4;

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

		$numeroPagina = 0;

		$paginator = $class -> get('knp_paginator');
		$pagination = $paginator -> paginate($query, $class -> getRequest() -> query -> get('page', $numeroPagina), ListadosController::tamanio
		);

		//$array = UtilitiesAPI::devuelveRecursosExistentes($array,$class);
		$array['resources'] = $pagination -> getItems();
		var_dump($array['resources']);
		exit;
		

		$array['izquierda'] =  $numeroPagina - 1;
		$array['derecha'] =  $numeroPagina + 1;
		
		$dataPaginacion = $pagination->getPaginationData();
		
		if($array['derecha'] > $dataPaginacion['pageRange']) $array['derecha'] =0;
		
		for($i=0;$i<count($array['articles']);$i++){
			$array['images'][$i] = $class -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsResource') -> find($array['articles'][$i]->getMedia()); 
			$array['articles'][$i]->setContent( substr ( $array['articles'][$i]->getContent() , 0, 300 ) .'...');
												   }
		return $array;


		return $array;
	}
	public function noticiasAction() {
			
		$peticion = $this -> getRequest();
		$doctrine = $this -> getDoctrine();
		$post = $peticion -> request;
		$numeroPagina = $post -> get("valor");
		
		if(!isset($numeroPagina)){
			$numeroPagina =1;
			return $this -> render('ProyectoFrontBundle:Default:noticias.html.twig',  ListadosController::contenidoNoticias($numeroPagina,$this));
		}
		else{
			$html = $this -> renderView('ProyectoFrontBundle:Default:noticias.html.twig', ListadosController::contenidoNoticias($numeroPagina,$this));
			$respuesta = new response(json_encode(array('variable'=>$html)));
			$respuesta -> headers -> set('content_type', 'aplication/json');
			return $respuesta;
		}
	}

	public function contenidoNoticias($numeroPagina,$class){
			
		$em = $class -> getDoctrine() -> getEntityManager();	
		$type = UtilitiesAPI::TIPO_NOTICIAS;
		
		$dql = "SELECT n FROM ProyectoPrincipalBundle:CmsArticle n WHERE n.type = :type1 and n.published = :published and n.lang = :lang order by n.dateCreated DESC";
			
		$query = $em -> createQuery($dql);
		$query -> setParameter('type1', $type);
		$query -> setParameter('published', 1);
		$query -> setParameter('lang', UtilitiesAPI::getLocale($this));

		$paginator = $class -> get('knp_paginator');
		$pagination = $paginator -> paginate($query, $class -> getRequest() -> query -> get('page', $numeroPagina), ListadosController::tamanio
		);

		$array['articles'] = $pagination -> getItems();
		$array['izquierda'] =  $numeroPagina - 1;
		$array['derecha'] =  $numeroPagina + 1;
		
		$dataPaginacion = $pagination->getPaginationData();
		
		if($array['derecha'] > $dataPaginacion['pageRange']) $array['derecha'] =0;
		
		for($i=0;$i<count($array['articles']);$i++){
			$array['images'][$i] = $class -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsResource') -> find($array['articles'][$i]->getMedia()); 
			$array['articles'][$i]->setContent( substr ( $array['articles'][$i]->getContent() , 0, 300 ) .'...');
												   }
		return $array;
	}
	public function paginacionEspecialAction($listado){
		
		$fecha = UtilitiesAPI::fechaHoy($this);
		$array = array('listado' => $listado);
		$fecha = new \DateTime($fecha);
		
		$array['fechas'][0]['fecha']= $fecha->format('Y-m-d');
		$array['fechas'][0]['dia']= $fecha->format('d').' '.UtilitiesAPI::letraDia($fecha->format('w'),$this);
		$fecha->add(new \DateInterval('P1D'));
		$array['fechas'][1]['fecha']= $fecha->format('Y-m-d');
		$array['fechas'][1]['dia']= $fecha->format('d').' '.UtilitiesAPI::letraDia($fecha->format('w'),$this);
		$fecha->add(new \DateInterval('P1D'));
		$array['fechas'][2]['fecha']= $fecha->format('Y-m-d');
		$array['fechas'][2]['dia']= $fecha->format('d').' '.UtilitiesAPI::letraDia($fecha->format('w'),$this);
		$fecha->add(new \DateInterval('P1D'));
		$array['fechas'][3]['fecha']= $fecha->format('Y-m-d');
		$array['fechas'][3]['dia']= $fecha->format('d').' '.UtilitiesAPI::letraDia($fecha->format('w'),$this);
		$fecha->add(new \DateInterval('P1D'));
		$array['fechas'][4]['fecha']= $fecha->format('Y-m-d');
		$array['fechas'][4]['dia']= $fecha->format('d').' '.UtilitiesAPI::letraDia($fecha->format('w'),$this);
		$fecha->add(new \DateInterval('P1D'));
		$array['fechas'][5]['fecha']= $fecha->format('Y-m-d');
		$array['fechas'][5]['dia']= $fecha->format('d').' '.UtilitiesAPI::letraDia($fecha->format('w'),$this);


		$hoy = getdate();
	 	$meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
	 	$month = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "Novemeber", "December");

	 	$mes = intval($hoy['mon']) - 1;

	 	if(UtilitiesAPI::getLocale($this)==0)$array['mes'] = $meses[$mes];
	 	else $array['mes'] = $month[$mes];
	 	
	 	$array['anio'] = intval($hoy['year']);
		
		return $this -> render('ProyectoFrontBundle:Default:paginacionespecial.html.twig', $array);
	}
}
