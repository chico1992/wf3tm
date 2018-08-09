<?php
namespace App\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use \Twig_Environment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Task;
use App\Form\TaskFormType;
use Symfony\Component\HttpFoundation\Request;

class TasksController extends Controller
{   


    public function tasks(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $task = new Task();
        $form = $this->createForm(
            TaskFormType::class,
            $task,
            ['standalone' => true]
        );

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($task);
            $manager->flush();
            return $this->redirectToRoute('task_list');
        }

        return $this->render(
            'task/tasks.html.twig',
            [
                'tasks' => $manager->getRepository(Task::class)->findAll(),
                'task_form' => $form->createView()
            
            ]
        );
    }

    public function task(Task $task, Request $request)
    {
        $form = $this->createForm(TaskFormType::class,$task,['standalone'=>true]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('task_detail',['task' => $task->getId()]);
        }
        return $this->render(
            'task/task.html.twig',
            [
                'task' => $task,
                'form' => $form->createView()
            ]            
        );
    }
}