<?php

namespace App\Controller;


use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Services\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use function MongoDB\BSON\toCanonicalExtendedJSON;

class ContactController extends AbstractController
{


    /**
     * @Route("/", name="utilisateur")
     */
    public function indexNew(Request $request, MailerService $mailerService,EntityManagerInterface $em,FlashyNotifier $flashyNotifier): Response
    {

        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);


     /*   if ($form->isSubmitted() && $form->isValid()) {*/
            $data = $form->getData();
            $utilisateur->setAdresseMail('konatenhamed@gmail.com');
            $mailerService->send(
                'Bienvenue à vous',
                $data->getIdentifiant(),
                'konatenhamed@gmail.com',
                "utilisateur/template.html.twig",
                [
                    'message' =>  'Bienvenue à vous,',
                    'identifiant' =>  $data->getIdentifiant(),
                    'email' =>  $data->getIdentifiant(),
                    'password' =>  $data->getPassWord(),
                ]
            );

            $em->persist($utilisateur);
            $em->flush();


        //}
        return $this->render('utilisateur/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
