<?php

namespace App\Controller;

use App\Entity\Feedback;
use App\Entity\User;
use App\Form\FeedbackType;
use App\Form\UserType;
use App\Repository\FeedbackRepository;
use App\Repository\UserRepository;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class FeedbackController extends AbstractController
{

    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @Route("/feedback", name="feedback")
     */
    public function index(Request $request, \Swift_Mailer $mailer)
    {

        $feedback = new Feedback();

        $form = $this->createForm(FeedbackType::class, $feedback);

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() ) {

            $entityManager = $this->getDoctrine()->getManager();


            $feedbackData = $form->getData();

            $datetime = $feedbackData->getDateContact()->getTimestamp() - 60;

            $lastSendedForms = $this->connection->executeQuery("SELECT f.date_contact FROM feedback as f, usr as u WHERE f.feedback_user_id=u.id AND u.user_ip=? AND f.date_contact > ?", [$request->getClientIp(), date('Y-m-d H:i:s', $datetime)], [\PDO::PARAM_STR, \PDO::PARAM_STR])->fetchAll();
            if( 2 < count($lastSendedForms) ){
                $form->addError(new FormError('Слишком много запросов с вашего адреса, пожалуйста, подождите минуту'));
                return $this->render('feedback/index.html.twig', [
                    'form' => $form->createView(),
                    'title' => 'Форма обратной связм'
                ]);
            }

            $captcha = $form->get('captcha')->getData();

            if( (int) $captcha !== (int) $form->get('result')->getData() ) {
                unset($form);
                $form = $this->createForm(FeedbackType::class, $feedback);
                $form->get('captcha')->addError(new FormError('Неправильно разгадана капча!'));
                return $this->render('feedback/index.html.twig', [
                    'form' => $form->createView(),
                    'title' => 'Форма обратной связм'
                ]);
            }

            $feedback
                ->setTextFeedback($feedbackData->getTextFeedback())
                ->setEmail($feedbackData->getUser()->getEmail());

            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $feedbackData->getEmail()]);

            if( !$user ){
                $user = new User();
                $user
                    ->setUsername($feedbackData->getUser()->getUsername())
                    ->setEmail($feedbackData->getUser()->getEmail())
                    ->setUserIp($request->getClientIp());
            }

            $feedback->setUser($user);
            $entityManager->persist($feedback);
            $entityManager->flush();


            $message = (new \Swift_Message('Your feedback message'))
                ->setFrom(getenv('EMAIL'))
                ->setTo($feedbackData->getUser()->getEmail())
                ->setBody(
                    $this->renderView('feedback/form.html.twig', ['text' => $feedback->getTextFeedback(), 'date' => $feedbackData->getDateContact(), 'history_link' => $this->generateUrl('history', ['email' => $feedbackData->getUser()->getEmail()])]),
                    'text/html'
                )
            ;
            $mailer->send($message);
            $this->addFlash('success', 'Message was send');

            return $this->redirectToRoute('feedback');
        }

        return $this->render('feedback/index.html.twig', [
            'form' => $form->createView(),
            'title' => 'Форма обратной связм'
        ]);
    }
}
