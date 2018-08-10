<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Project;
use App\Form\ProjectFormType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Document;


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

            $file = $project->getThumbnail();
            if($file){
                $document = new Document();
                $document->setPath($this->getParameter('upload_dir'))
                    ->setMimeType($file->getMimeType())
                    ->setName($file->getFilename());
                $file->move($this->getParameter('upload_dir'));

                $project->setThumbnail($document);
                $manager->persist($document);
            }

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