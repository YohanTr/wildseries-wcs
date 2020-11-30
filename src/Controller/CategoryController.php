<?php
// src/Controller/CategoryController.php
namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("categories", name="category_")
 */
class CategoryController extends AbstractController
{
    /**
     * Show all category from Category's entity
     *
     * @Route("/", name="index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        return $this->render(
            '/category/index.html.twig',
            ['categories' => $categories]
        );
    }
    /**
     * @Route("/{categoryName}"), methods={"GET"}, name="show")
     * @return Response A response instance
     */
    public function show(string $categoryName): Response
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => $categoryName]);
        if(!$categories) {
            throw $this->createNotFoundException('Aucune catégorie nommée ' . $categoryName);
        }
         $programs = $this->getDoctrine()
             ->getRepository(Program::class)
             ->findBy(['category' => $categories->getId()], ['id' => 'desc'], 3);

        return $this->render("/category/show.html.twig", [
            'category' => $categories,
            'programs' => $programs
        ]);
    }
}