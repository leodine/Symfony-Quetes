<?php
// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\Program;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WildController extends AbstractController
{
    /**
 * Show all rows from Program’s entity
 *
 * @Route("/wild", name="wild_index")
 * @return Response A response instance
 */
 public function index(): Response
 {
      $programs = $this->getDoctrine()
          ->getRepository(Program::class)
          ->findAll();

      if (!$programs) {
          throw $this->createNotFoundException(
          'No program found in program\'s table.'
          );
      }

      return $this->render(
              'wild/index.html.twig',
              ['programs' => $programs]
      );
}
    
    /**
    * Getting a program with a formatted slug for title
    *
    * @param string $slug The slugger
    * @Route("/show/{slug<^[a-z0-9-]+$>}", defaults={"slug" = null}, name="wild_show")
    * @return Response
    */
    public function show(?string $slug):Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with '.$slug.' title, found in program\'s table.'
            );
        }

        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug'  => $slug,
        ]);
    }
    
    /**
    * Getting a program with a category
    *
    * @param string $categoryName the category
    * @Route("/show/{categoryName}", defaults={"categoryName" = null}, name="wild_show_by_category")
    * @return Response
    */
    public function showByCategory(?string $categoryName):Response
    {
    	if (!$categoryName) {
            throw $this
                ->createNotFoundException('No category has been sent to find a program in program\'s table.');
        }
        
        $category = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneCategoryByName(['category' => mb_strtolower($categoryName)]);
        if (!$category) {
            throw $this->createNotFoundException(
                'No category with '.$categoryName.' title, found in category\'s table.'
            );
        }
        
        $id = $category->getId();
        
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['category_id' => $id]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with '.$slug.' title, found in program\'s table.'
            );
        }

        return $this->render('wild/category.html.twig', [
            'program' => $program, 
            'category' => $categoryName,           
        ]);
    }
}

