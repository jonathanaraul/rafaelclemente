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
//use Proyecto\PrincipalBundle\Entity\CmsReservation;
use Proyecto\PrincipalBundle\Entity\CmsPage;
use Proyecto\PrincipalBundle\Entity\CmsGallery;
use Proyecto\PrincipalBundle\Entity\CmsGalleryResource;
use Proyecto\PrincipalBundle\Entity\CmsResource;



class DefaultController extends Controller {

	const tamanioGaleria = 11;
	const tamanioPagina = 3;

	public function indexAction() {

	 return $this->redirect($this->generateUrl('proyecto_front_homepage',array('_locale' => 'es')));
	}
	public function inicioAction() {

		$firstArray = UtilitiesAPI::getDefaultContent('inicio', $this);
		$secondArray = array();
		$secondArray['gallery'] = UtilitiesAPI::getGalleryResources(1, $this);
		$secondArray['idpage'] = null;
		
		$array = array_merge($firstArray, $secondArray);
		return $this -> render('ProyectoFrontBundle:Default2:inicio.html.twig', $array);
	}
	public function actualizaGaleriaAction(){
		$peticion = $this -> getRequest();
		$doctrine = $this -> getDoctrine();
		$post = $peticion -> request;
		$em = $this -> getDoctrine() -> getManager();

		//INICIALIZAR VARIABLES

		$numeroPagina = $post -> get("pagina");
		$galeria = $post -> get("galeria");

		$secondArray = array();
		//$numeroPagina = 0;
		$secondArray['gallery'] = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsGallery') -> find($galeria);
		$secondArray['galleryResources'] = UtilitiesAPI::getGalleryResources($galeria, $this);
		$secondArray['paginator'] = array('next'=>null,'prev'=>null,
										      'numeroPagina'=>$numeroPagina,
											  'tamanio'=>DefaultController::tamanioGaleria,
											  'gallery'=>$galeria); 

		if(count($secondArray['galleryResources'])>DefaultController::tamanioGaleria){
			
			if($numeroPagina>0)
				$secondArray['paginator']['prev'] = $numeroPagina - 1;

			$limiteSuperior = ($numeroPagina  * DefaultController::tamanioGaleria) + DefaultController::tamanioGaleria;

			if(count($secondArray['galleryResources'])>$limiteSuperior)
				$secondArray['paginator']['next'] = $numeroPagina + 1;

			$secondArray['galleryResources'] = UtilitiesAPI::recortaArray($secondArray['galleryResources'],$secondArray['paginator'], $this);

		}
		$htmlRecursos = $this -> renderView('ProyectoFrontBundle:Helpers2:galeriaRecursos.html.twig', $secondArray);
		$htmlPaginacion = $this -> renderView('ProyectoFrontBundle:Helpers2:galeriaRecursosPaginacion.html.twig', $secondArray);

		$estado = true;
		$respuesta = new response(json_encode(array('htmlRecursos' => $htmlRecursos,'htmlPaginacion' => $htmlPaginacion)));
		$respuesta -> headers -> set('content_type', 'aplication/json');
		return $respuesta;
	}
	public function actualizaExposicionesAction() {
			
		$peticion = $this -> getRequest();
		$doctrine = $this -> getDoctrine();
		$post = $peticion -> request;
		$numeroPagina = $post -> get("pagina");

		$secondArray = array();

		$secondArray['exhibitions'] = UtilitiesAPI::getExhibitions($numeroPagina,DefaultController::tamanioPagina,$this);

		$html = $this -> renderView('ProyectoFrontBundle:Helpers2:exposiciones.html.twig', $secondArray);
		$respuesta = new response(json_encode(array('htmlRecursos'=>$html)));
		$respuesta -> headers -> set('content_type', 'aplication/json');
		return $respuesta;
		
	}
	public function pageAction($id,$friendlyname) {
		
		$firstArray = UtilitiesAPI::getDefaultContent('contacto', $this);
		$secondArray = array();
		$secondArray['page'] = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsPage') -> find($id);
		$secondArray['media'] = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsResource') -> find($secondArray['page']->getResource());
		$secondArray['idpage'] = $secondArray['page']->getId();
		$secondArray['gallery'] = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsGallery') -> findOneByPage($secondArray['idpage']);
		$secondArray['galleryResources'] = null;
		$secondArray['exhibitions'] = null;
		
		if(is_null($secondArray['gallery'])==false){
			$numeroPagina = 0;
			$secondArray['galleryResources'] = UtilitiesAPI::getGalleryResources($secondArray['gallery']->getId(), $this);
			$secondArray['paginator'] = array('next'=>null,'prev'=>null,
										      'numeroPagina'=>$numeroPagina,
											  'tamanio'=>DefaultController::tamanioGaleria,
											  'gallery'=>$secondArray['gallery']->getId()); 

			if(count($secondArray['galleryResources'])>15){
				if($numeroPagina>0)
					$secondArray['paginator']['prev'] = $numeroPagina - 1;

				$limiteSuperior = ($numeroPagina  * DefaultController::tamanioGaleria) + DefaultController::tamanioGaleria;

				if(count($secondArray['galleryResources'])>$limiteSuperior)
					$secondArray['paginator']['next'] = $numeroPagina + 1;

				$secondArray['galleryResources'] = UtilitiesAPI::recortaArray($secondArray['galleryResources'],$secondArray['paginator'], $this);
			}
		}
		
		if($secondArray['page']->getScheme()==1){
			$secondArray['exhibitions'] = UtilitiesAPI::getExhibitions(1,DefaultController::tamanioPagina,$this);
		}

		$template  = $secondArray['page']->getTemplate();
		$array = array_merge($firstArray, $secondArray);
		return $this -> render('ProyectoFrontBundle:Default2:page'.$template.'.html.twig', $array);
	}
	public function articleAction($id) {
		$firstArray = UtilitiesAPI::getDefaultContent('noticias', $this);
		$secondArray = array();
		$secondArray['article'] = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsArticle') -> find($id);
		$secondArray['resource'] = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsResource') -> find($secondArray['article']->getBackground());
		$secondArray['background'] = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsResource') -> find($secondArray['article']->getBackground());
		$secondArray['media'] = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsResource') -> find($secondArray['article']->getResource());
		$secondArray['theme'] = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsTheme') -> find($secondArray['article']->getTheme());
		$secondArray['idpage'] = null;

		$secondArray['path'] = $secondArray['article']->getPdf();


		if($secondArray['path']!=null && trim($secondArray['path'])!=""){
			if (file_exists($secondArray['article']->getWebPath())) {
   				$secondArray['path'] = $secondArray['article']->getWebPath();
			}
			else{
				$secondArray['path'] = null;
			}
		}
		else{
			$secondArray['path'] = null;
		}



		if($secondArray['article']->getType()==0){
			$secondArray['idpage'] = 1;
		}
		else if($secondArray['article']->getType()==1 || $secondArray['article']->getType()==2){
			
			if($secondArray['article']->getType()==1)$secondArray['idpage'] = 37;
			else $secondArray['idpage'] = 5;
			
			//$secondArray['dates'] = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsDate') -> findByArticle();
			
			$em = $this -> getDoctrine() -> getEntityManager();	
		
			$dql = "SELECT n.date
		        FROM ProyectoPrincipalBundle:CmsDate n
		        WHERE n.article = :article
		        ORDER by n.date ASC";
		
			$query = $em -> createQuery($dql);
			$query -> setParameter('article', $secondArray['article']->getId());
		
			$secondArray['dates'] = $query -> getResult();

			//var_dump($secondArray['dates']);
			//exit;


			if(count($secondArray['dates'])>0){
				$helper = array( );

				for ($i=0; $i < count($secondArray['dates']); $i++) { 
					$helper[$i] = UtilitiesAPI::fechaPresentacion($secondArray['dates'][$i]['date'],$this);
				}
			 $secondArray['dates'] = $helper;
			}

		}

		$array = array_merge($firstArray, $secondArray);
		return $this -> render('ProyectoFrontBundle:Default:article.html.twig', $array);
	}
	
	public function resourceAction($galerianombre,$idgaleria,$recursonombre,$idrecurso){

		
		$firstArray = UtilitiesAPI::getDefaultContent('contacto', $this);
		$secondArray = array();
		$secondArray['page'] = UtilitiesAPI::findTextResource($idrecurso, $this);
		$secondArray['idpage'] = null;


		$secondArray['media'] = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsResource') -> find($idrecurso);
		$galleryResources = UtilitiesAPI::getGalleryResources($idgaleria,$this);

		$prev = null;
		$next = null;
		$nombrePrev= '';
		$nombreNext= '';
		$encontrado = false;

		for ($i=0; $i < count($galleryResources) ; $i++) {

			$anterior = $i - 1;
			$siguiente = $i + 1;

			if($galleryResources[$i]->getId() == $idrecurso){
				if($anterior >=0){
					$prev = $galleryResources[$i-1]->getId();
					$nombrePrev = $galleryResources[$i-1]->getFriendlyName();
				}
				
				if($siguiente <= (count($galleryResources) - 1) ){
					$next = $galleryResources[$i+1]->getId();	
					$nombreNext = $galleryResources[$i+1]->getFriendlyName();
				}
							
			}
		}

		$secondArray['prev']= null;
		$secondArray['next']= null;

		if(!is_null($prev)){
			$secondArray['prev'] = $this -> generateUrl('proyecto_front_resource', 
				array('galerianombre' => $galerianombre,
					  'idgaleria' => $idgaleria,
					  'recursonombre' => $nombrePrev,
					  'idrecurso' => $prev
					));
		}
		if(!is_null($next)){
			$secondArray['next']= $this -> generateUrl('proyecto_front_resource', 
				array('galerianombre' => $galerianombre,
					  'idgaleria' => $idgaleria,
					  'recursonombre' => $nombreNext,
					  'idrecurso' => $next
					));
		}

		//var_dump($secondArray);
		//exit;
		$array = array_merge($firstArray, $secondArray);
		return $this -> render('ProyectoFrontBundle:Default2:resource.html.twig', $array);
	}

}
