<?php

namespace Proyecto\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Proyecto\PrincipalBundle\Entity\User;
use Proyecto\PrincipalBundle\Entity\Data;
use Proyecto\PrincipalBundle\Entity\Categoria;
use Proyecto\PrincipalBundle\Entity\CmsPage;




class HelpersController extends Controller
{
    public function menuMobileAction()
    {
    	$locale = UtilitiesAPI::getLocale($this);
	
		$em = $this->getDoctrine()->getManager();
		
		$query = $em -> createQuery('SELECT d
    								 FROM ProyectoPrincipalBundle:CmsPage d
   	 								 WHERE d.lang      = :locale and
   	 								       d.published = :published
    								 ORDER BY d.rank ASC') 
    		   -> setParameter('locale', $locale)
			   -> setParameter('published', 1);

		$array['objects'] = $query -> getResult();
	
        return $this->render('ProyectoFrontBundle:Helpers:menu-mobile.html.twig', $array);
    }
    public function menuAction($idpage)
    {
    	$locale = UtilitiesAPI::getLocale($this);
	
		$em = $this->getDoctrine()->getManager();
		
		$query = $em -> createQuery('SELECT d
    								 FROM ProyectoPrincipalBundle:CmsPage d
   	 								 WHERE d.lang      = :locale and
   	 								       d.published = :published
    								 ORDER BY d.rank ASC') 
    		   -> setParameter('locale', $locale)
			   -> setParameter('published', 1);

		$array['objects'] = $query -> getResult();
		$array['idpage'] = $idpage;
	
        return $this->render('ProyectoFrontBundle:Helpers:menu.html.twig', $array);
    }
}
