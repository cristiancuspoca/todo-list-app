<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Actividad;
use AppBundle\Entity\Categoria;
use AppBundle\Entity\Usuario;

/**
 * Controlador principal
 */
class ListaController extends Controller
{

    /**
     * @Route("/", name="task_list")
     */
    public function indexAction(Request $request)
    {
        $session = $request->getSession();
        $user = $this->getUser();
        $id_user = $user->getId();
        
        $q = $request->request->get('q');
        $repository = $this->getDoctrine()->getRepository(Categoria::class);
        if (!!$q) {
            $categories = $repository->search_category_tasks($q, $id_user);
            dump($categories);
        } else {
            $categories = $repository->all_category_tasks($id_user);
        }

        /*$categories = $repository->findAll();
        foreach ($categories as $i => $cat) {
            // Solicitamos la carga de las actividades relacionadas
            $cat->getActividadesFull();
        }*/
        return $this->render('default/tasks/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'categories' => $categories
        ]);
    }
}
