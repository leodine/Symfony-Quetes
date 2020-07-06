<?php
// src/Controller/WildController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WildController extends AbstractController
{
    /**
     * @Route("/wild/show/{slug}", name="wild_show")
     */
    public function show($slug)
    {
    	// string only contain the a to z , 0 to 9 or '-'
    	if(preg_match("/^[a-z0-9-]+$/", $slug) == 1) 
    	{
    		if ( substr_count($slug, '-') >= 1 )
    		{
	    		$slug = str_replace("-", " ", "$slug");	    		
    		}
    		$slug = ucwords($slug);
    		return $this->render('wild/show.html.twig', ['slug' => $slug]);
	}
	else
	{
		return $this->render('wild/show.html.twig', ['slug' => "entre a et z (minuscules uniquement), des 						chiffres de 0 à 9 ou des tirets -"]);
	}        	
    }
}

