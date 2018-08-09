<?php

namespace App\Controller;

use App\Form\UserFormType;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Role;
use App\Form\RoleFormType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Service\MailSender;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;


class  AdminController extends Controller
{

    public function default(Request $request, EncoderFactoryInterface $factory ,MailSender $sender)
    {
        $user = new User();
        $form = $this->createForm(
            UserFormType::class,
            $user,
            ['standalone' => true]
        );

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $encoder = $factory->getEncoder(User::class);

            $encodedPassword = $encoder->encodePassword(
                $user->getPassword(),
                $user->getUsername()
            );
            $user->setPassword($encodedPassword);

            // insert data in database
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            $sender->send($user);

            // redirect to user list GET
            return $this->redirectToRoute('admin_default');
        }

        return $this->render(
            'Admin\default.html.twig',
            [
                'user_form' => $form->createView(),       
            ]
        );
    }

    public function jsonUserList()
    {
        $userRepository = $this->getDoctrine()
            ->getManager()
            ->getRepository(User::class);

        $userList = $userRepository->findAll();

        $serializer = $this->getSerializer();

        return new JsonResponse(
            $serializer->serialize($userList,'json'),
            200,
            [],
            true
        );
    }

    public function getSerializer() : SerializerInterface
    {
        return $this->get('serializer');
    }
}