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
use Proyecto\PrincipalBundle\Entity\CmsReservation;


class ReservationController extends Controller {

	public function listAction(Request $request) {

		$locale = UtilitiesAPI::getLocale($this);
		$type = 'reservation';
		$config = UtilitiesAPI::getConfig($type,$this);
		$url = $this -> generateUrl('proyecto_principal_reservation_list');
		$firstArray = UtilitiesAPI::getDefaultContent('RESERVATION', $config['list'], $this);
		$firstArray['type'] = $config['type'];
		
		$filtros['checked'] = array(0 => 'Sin chequear', 1 => 'Chequeado' );
		
		///////////////////////////////////////////////////////////////////////////////////////////////////
		$data = new CmsReservation();
		$form = $this -> createFormBuilder($data) 
		-> add('name', 'text', array('required' => false))
		-> add('checked', 'choice', array('choices' => $filtros['checked'], 'required' => false, )) 
		-> getForm();

		$em = $this -> getDoctrine() -> getEntityManager();

		if ($this -> getRequest() -> isMethod('POST')) {
			$form -> bind($this -> getRequest());

			if ($form -> isValid()) {
				//echo 'chequeadi '.$data -> getChecked();
				//exit;
				$dql = "SELECT n FROM ProyectoPrincipalBundle:CmsReservation n ";
				$where = false;
				/*
				if (!(trim($data -> getCategory()) == false)) {

					if ($where == false) {
						$dql .= 'WHERE ';
						$where = true;
					}
					$dql .= ' n.category = :category ';

				}*/
				if (!(trim($data -> getName()) == false)) {

					if ($where == false) {
						$dql .= 'WHERE ';
						$where = true;
					} else {
						$dql .= 'AND ';
					}

					$dql .= " n.name like :name ";

				}
				if (is_numeric($data -> getChecked())) {

					if ($where == false) {
						$dql .= 'WHERE ';
						$where = true;
					} else {
						$dql .= 'AND ';
					}
					$dql .= ' n.checked = :checked ';
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

		/*		if (!(trim($data -> getCategory()) == false)) {
					$query -> setParameter('category', $data -> getCategory());
				}*/
				if (!(trim($data -> getName()) == false)) {
					$query -> setParameter('name', '%' . $data -> getName() . '%');
				}
				if (is_numeric($data -> getChecked())) {
					$query -> setParameter('checked', intval($data -> getChecked()));
				}
				$query -> setParameter('lang', $locale);

			}
		}
		//////////////////////////////////////////////////////////////////////////////////////////////////
		else {
			$dql = "SELECT n FROM ProyectoPrincipalBundle:CmsReservation n
					 WHERE n.checked = :checked AND n.lang = :lang  ";
			$query = $em -> createQuery($dql);	
			$query -> setParameter('checked', 0);
			$query -> setParameter('lang', $locale);

		}

		$paginator = $this -> get('knp_paginator');
		$pagination = $paginator -> paginate($query, $this -> getRequest() -> query -> get('page', 1), 15);

		$objects = $pagination -> getItems();
		$auxiliar = array();

		for ($i = 0; $i < count($objects); $i++) {
			$auxiliar[$i]['id'] = $objects[$i] -> getId();
			$auxiliar[$i]['article'] =  $objects[$i]  -> getArticle()-> getName();
			$auxiliar[$i]['name'] =  $objects[$i] -> getName();
			$auxiliar[$i]['phone'] = $objects[$i] -> getPhone();
			$auxiliar[$i]['email'] = $objects[$i] -> getEmail();
			$auxiliar[$i]['rdate'] = $objects[$i] -> getRDate();
			$auxiliar[$i]['date'] = $objects[$i] -> getDate()->format('d/m/Y H:m');
			$auxiliar[$i]['checked'] = $objects[$i] -> getChecked();
		}
		$objects = $auxiliar;

		//$secondArray = array('pagination' => $pagination, 'objects' => $objects,  'url' => $url);
		$secondArray = array('pagination' => $pagination, 'filtros' => $filtros, 'objects' => $objects, 'form' => $form -> createView(), 'url' => $url);
		
		$array = array_merge($firstArray, $secondArray);

		return $this -> render('ProyectoPrincipalBundle:Reservation:List.html.twig', $array);
	}
	
	public function checkedAction() {

		$peticion = $this -> getRequest();
		$doctrine = $this -> getDoctrine();
		$post = $peticion -> request;
		//INICIALIZAR VARIABLES

		$id = $post -> get("id");
		$tarea = intval($post -> get("tarea"));

		$em = $this -> getDoctrine() -> getManager();
		$object = $em -> getRepository('ProyectoPrincipalBundle:CmsReservation') -> find($id);
		$object -> setChecked($tarea);
		$em -> flush();
		
		$estado = true;
		$respuesta = new response(json_encode(array('estado' => $estado)));
		$respuesta -> headers -> set('content_type', 'aplication/json');
		return $respuesta;
	}


}
