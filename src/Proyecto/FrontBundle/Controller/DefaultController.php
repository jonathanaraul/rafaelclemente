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


class DefaultController extends Controller {

	public function indexAction() {
	 return $this->redirect($this->generateUrl('proyecto_front_homepage',array('_locale' => 'es')));
	}
	public function inicioAction() {

		$firstArray = UtilitiesAPI::getDefaultContent('inicio', $this);
		$secondArray = array();
		$secondArray['backgrounds'] = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsResource') -> findByHome(1);
		$secondArray['idpage'] = null;
		$secondArray['theme'] = array('color'=>'black','id'=>0);
		
		$array = array_merge($firstArray, $secondArray);
		return $this -> render('ProyectoFrontBundle:Default:inicio.html.twig', $array);
	}
	public function pageAction($id,$friendlyname) {
		
		$firstArray = UtilitiesAPI::getDefaultContent('contacto', $this);
		$secondArray = array();
		$secondArray['page'] = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsPage') -> find($id);
		$secondArray['background'] = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsResource') -> find($secondArray['page']->getBackground());
		$secondArray['media'] = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsResource') -> find($secondArray['page']->getMedia());
		$secondArray['theme'] = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsTheme') -> find($secondArray['page']->getTheme());
		$secondArray['path'] = $secondArray['page']->getPath();

		if($secondArray['path']!=null && trim($secondArray['path'])!=""){
			if (file_exists($secondArray['page']->getWebPath())) {
   				$secondArray['path'] = $secondArray['page']->getWebPath();
			}
			else{
				$secondArray['path'] = null;
			}
		}
		else{
			$secondArray['path'] = null;
		}


		$secondArray['idpage'] = $secondArray['page']->getId();
		$secondArray['articles'] = null;
		$secondArray['listado'] = UtilitiesAPI::esListado($secondArray['idpage'],$this);
		$secondArray['images'] = array();
		
		$array = array_merge($firstArray, $secondArray);
		return $this -> render('ProyectoFrontBundle:Default:page.html.twig', $array);
	}
	public function articleAction($id) {
		$firstArray = UtilitiesAPI::getDefaultContent('noticias', $this);
		$secondArray = array();
		$secondArray['article'] = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsArticle') -> find($id);
		$secondArray['resource'] = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsResource') -> find($secondArray['article']->getBackground());
		$secondArray['background'] = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsResource') -> find($secondArray['article']->getBackground());
		$secondArray['media'] = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsResource') -> find($secondArray['article']->getMedia());
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
	
	public function reservationAction($id, Request $request) {
		//$prueba = UtilitiesAPI::sendMail('Elecciones', array('name' => 'Juan','phone' =>'04249271991', 'email'=>'jonathan.araul@gmail.com' ),$this);
		$firstArray = UtilitiesAPI::getDefaultContent('reservation', $this);
		$secondArray = array();
		$secondArray['article'] = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsArticle') -> find($id);
		$locale = UtilitiesAPI::getLocale($this);
		///////////////////////////////////////////////////////////////////////////////////////////////////
		$data = new CmsReservation();
		$form = $this -> createFormBuilder($data) 
		-> add('name', 'text', array('required' => false))
		-> add('phone', 'text', array('required' => false)) 
		-> add('email', 'text', array('required' => false)) 
		-> add('rdate', 'text', array('required' => false)) 
		-> getForm();

		$em = $this -> getDoctrine() -> getEntityManager();
		$secondArray['message'] = '';

		if ($this -> getRequest() -> isMethod('POST')) {
			$form -> bind($this -> getRequest());
			$data -> setLang($locale);
			$data -> setDate(new \DateTime());
			$data -> setChecked(false);
			$data -> setArticle($secondArray['article']);
			
			$em -> persist($data);
			$em -> flush();

			$secondArray['message'] = 'Estimado(a) '.ucwords($data -> getName()).' su reserva ha sido guardada exitosamente...';
			if($locale==1){
				$secondArray['message'] = 'Dear '.ucwords($data -> getName()).' your booking has been saved successfully...';
			}
			
														}
		
		$secondArray['form'] = $form -> createView();
		$secondArray['url'] =  $this -> generateUrl('proyecto_front_reservation', array('id' => $id));
		$secondArray['theme'] = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsTheme') -> find(6);
		
		$secondArray['background'] = '-';

			
		$helper = $this -> getDoctrine() -> getRepository('ProyectoPrincipalBundle:CmsResource') -> find(56);
		if($helper!= NULL){
					$secondArray['background'] = $helper  -> getWebPath();
				}
			
		$secondArray['idpage'] = null;

		$array = array_merge($firstArray, $secondArray);
		return $this -> render('ProyectoFrontBundle:Default:reservation.html.twig', $array);
	}


}
