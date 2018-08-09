<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Project;
use App\Form\ProjectFormType;
use Symfony\Component\HttpFoundation\Request;


class ProjectsController extends Controller
{

    public function projects(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $project = new Project();
        $form = $this->createForm(
            ProjectFormType::class,
            $project,
            ['standalone' => true]
        );

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            // insert data in database
            $manager->persist($project);
            $manager->flush();
            
            // redirect to user list GET
            return $this->redirectToRoute('project_list');
        }

        return $this->render(
            'project/projects.html.twig',
            [
                'projects' => $manager->getRepository(Project::class)->findAll(),
                'project_form' => $form->createView()
            ]
        );
    }
}