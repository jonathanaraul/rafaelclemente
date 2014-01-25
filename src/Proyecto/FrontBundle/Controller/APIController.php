<?php

namespace Proyecto\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Proyecto\PrincipalBundle\Entity\Data;
use Proyecto\PrincipalBundle\Entity\Categoria;

class APIController extends Controller
{
    public static  function findDataByTitleCategory($title,$class)
    {	
		$em = $class->getDoctrine()->getManager();
		$query = $em -> createQuery('SELECT d
    								 FROM ProyectoPrincipalBundle:Data d,
    	 								  ProyectoPrincipalBundle:Categoria c
   	 								 WHERE d.categoria = c.id AND
   	 								       c.titulo = :titulo
    								 ORDER BY d.titulo DESC') -> setParameter('titulo', $title);	
     	return $query -> getResult();
    }
}
