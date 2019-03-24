<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use AppBundle\Form\CategoriaType;
use AppBundle\Entity\Categoria;


/**
 * Clase para gestionar las categorias
 * 
 * @Route("/category")
 */
class CategoriaController extends Controller
{
    /**
     * Lista de categorias
     * @Route("/list", name="category_list")
     */
    public function listCategories(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Categoria::class);
        $categories = $repository->findAll();

        return $this->render('default/categories/list.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * Metodo para capturar informacion de un formulario
     * para crear una nueva categoria
     * 
     * @Route("/new", name="category_new")
     */
    public function newCategory(Request $request) {
        $category = new Categoria();
        // Creamos el formulario
        $form = $this->createForm(CategoriaType::class, $category);

        // Recogemos la informacion de la peticion
        $form->handleRequest($request);

        // Se valida si los datos estan correctos y si se envio la peticion
        if ($form->isSubmitted() && $form->isValid()) {
            // Obtenemos los datos de la informacion
            $category = $form->getData();

            // Obtenemos el entityManager
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category); // Indicamos que vamos a guardar los datos
            $entityManager->flush(); // Confirmamos los cambios en la base de datos (commit)

            return $this->redirectToRoute('category_list');
        }

        return $this->render('default/categories/new.html.twig', [
            'form' => $form->createView(),
            'title' => 'Nueva Categoría'
        ]);
    }


    /**
     * @Route("/update/{id}", name="category_update", requirements={"id"="\d+"})
     */
    public function editCategory(Request $request, $id) {
        if ($id) {
            $entityManager = $this->getDoctrine()->getManager();
            $category = $entityManager->getRepository(Categoria::class)->find($id);

            if (!$category) {
                throw $this->createNotFoundException('La categoría '.$id.' no existe');
            } else {
                // Creamos el formulario
                $form = $this->createForm(CategoriaType::class, $category);

                // Recogemos la informacion de la peticion
                $form->handleRequest($request);

                // Se valida si los datos estan correctos y si se envio la peticion
                if ($form->isSubmitted() && $form->isValid()) {
                    // Obtenemos los datos de la informacion
                    $category = $form->getData();

                    // Obtenemos el entityManager
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($category); // Indicamos que vamos a guardar los datos
                    $entityManager->flush(); // Confirmamos los cambios en la base de datos (commit)

                    return $this->redirectToRoute('category_list');
                }
            }

            return $this->render('default/categories/new.html.twig', [
                'form' => $form->createView(),
                'title' => 'Actualizar Categoría'
            ]);
        } else {
            return $this->redirectToRoute('category_list');
        }
    }
}
