<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

use AppBundle\Form\ActividadType;
use AppBundle\Entity\Actividad;

/**
 * Clase para gestionar las actividades/tareas
 * 
 * @Route("/task")
 */
class ActividadController extends Controller
{

    /**
     * @Route("/new", name="task_new")
     */
    public function newTask(Request $request) {
        $task = new Actividad();
        $task->setEstado(false);
        $task->setFechaCreacion(new \DateTime());
        
        // Creamos el formulario
        $form = $this->createForm(ActividadType::class, $task);

        // Recogemos la informacion de la peticion
        $form->handleRequest($request);

        // Se valida si los datos estan correctos y si se envio la peticion
        if ($form->isSubmitted() && $form->isValid()) {
            // Obtenemos los datos del formulario
            $task = $form->getData();

            // Obtenemos el entityManager
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task); // Indicamos que vamos a guardar los datos
            $entityManager->flush(); // Confirmamos los cambios en la base de datos (commit)

            return $this->redirectToRoute('task_list');
        }

        return $this->render('default/tasks/new.html.twig', [
            'form' => $form->createView(),
            'title' => 'Nueva Actividad',
        ]);
    }


    /**
     * @Route("/update/{id}", name="task_update", requirements={"id"="\d+"})
     */
    public function updateTask(Request $request, $id) {
        if ($id) {
            $entityManager = $this->getDoctrine()->getManager();
            $task = $entityManager->getRepository(Actividad::class)->find($id);

            if (!$task) {
                throw $this->createNotFoundException('La actividad '.$id.' no existe');
            } else {
                // Creamos el formulario
                $form = $this->createForm(ActividadType::class, $task);

                // Recogemos la informacion de la peticion
                $form->handleRequest($request);

                // Se valida si los datos estan correctos y si se envio la peticion
                if ($form->isSubmitted() && $form->isValid()) {
                    // Obtenemos los datos de la informacion
                    $task = $form->getData();

                    // Obtenemos el entityManager
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($task); // Indicamos que vamos a guardar los datos
                    $entityManager->flush(); // Confirmamos los cambios en la base de datos (commit)

                    return $this->redirectToRoute('task_list');
                }
            }

            return $this->render('default/tasks/new.html.twig', [
                'form' => $form->createView(),
                'title' => 'Actualizar Actividad'
            ]);
        } else {
            return $this->redirectToRoute('task_list');
        }
    }


    /**
     * Eliminar actividad
     * 
     * @Route("/remove/{id}", name="task_remove", requirements={"id"="\d+"})
     */
    public function removeTask(Request $request, $id) {
        if ($id) {
            $entityManager = $this->getDoctrine()->getManager();
            $task = $entityManager->getRepository(Actividad::class)->find($id);

            if (!$task) {
                throw $this->createNotFoundException('La actividad '.$id.' no existe');
            } else {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($task);
                $entityManager->flush();
                return $this->redirectToRoute('task_list');
            }
        } else {
            return $this->redirectToRoute('task_list');
        }
    }


    /**
     * Marcar una actividad como realizada
     * 
     * @Route("/mark", name="task_mark")
     */
    public function markedTask(Request $request) {
        $id = $request->request->get('id');

        $jsonData = array('status' => '', 'data' => '');
        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository(Actividad::class)->find($id);


        if (!!$task) {
            if ($task->getEstado() == 1) {
                $task->setEstado(FALSE);
            } elseif ($task->getEstado() == 0) {
                $task->setEstado(TRUE);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            $jsonData['status'] = 'success';
            $jsonData['data'] = $task->getEstado();
            return new JsonResponse($jsonData);
        }
    }

    /**
     * Realizar busqueda ajax por nombre
     * 
     * @Route("/search", name="task_search_ajax")
     */
    public function searchName(Request $request) {
        $q = $request->request->get('q');
        $jsonData = array('status' => '', 'data' => '');

        if ($request->isXmlHttpRequest() && !!$q) {
            $entityManager = $this->getDoctrine()->getManager();
            $repository = $entityManager->getRepository(Actividad::class);
            $tasks = $repository->searchByName($q);
            $resData = array();
            foreach ($tasks as $i => $task) {
                array_push($resData, $task->getNombre());
            }
            $jsonData = array('status' => 'success', 'data' => $resData);
            return new JsonResponse($jsonData);
        }
    }
}