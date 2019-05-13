<?php


namespace App\Controller;


use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{

    /**
     * @return Response
     * @Route("/contact", name="contactForm")
     */
    public function contact(Request $request, \Swift_Mailer $mailer) : Response
    {
        $contact = New Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        $userMail = $form->get('mail')->getData();
        $mailSubject = $form->get('subject')->getData();



        if($form->isSubmitted() && $form->isValid())
        {
            $message = (new \Swift_Message($mailSubject))
                ->setFrom('drumsandallcontact@gmail.com')
                ->setTo('drumsandallcontact@gmail.com')
                ->setBody(
                    $this->renderView(
                        'contact/email.html.twig',
                    ['pseudo' => $form->get('pseudo')->getData(),
                     'subject' => $mailSubject,
                     'message' => $form->get('message')->getData(),
                      'mail' => $userMail,
                        ]
                    ),
                    'text/html');

            $mailer->send($message);
            $this->addFlash('success','Votre message a bien été envoyé');


            return $this->render('contact/contact_form.html.twig',[
                'form'=> $form->createView(),
                'user' => $this->getUser()
            ] );

        }

        return $this->render('contact/contact_form.html.twig',[
            'form'=> $form->createView(),
            'user' => $this->getUser()
        ] );
    }

    /**
     * @Route("/aboutDrums&all", name="aboutdrumsandall")
     */
    public function aboutDrumsandall() : Response
    {
       return $this->render('contact/aboutdrumsandall.html.twig', [
            'user' => $this->getUser()
        ]);
    }
}