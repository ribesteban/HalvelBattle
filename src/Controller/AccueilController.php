<?php
namespace App\Controller;

use App\Form\UserType;
use App\Entity\User;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccueilController extends Controller
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('accueil/index.html.twig');
    }


    /**
     * @Route ("/accueil/inscription", name="inscription")
     */
    public function inscription(Request $request, UserPasswordEncoderInterface $passwordEncoder,  \Swift_Mailer $mailer)
    {

        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            // Par defaut l'utilisateur aura toujours le rôle ROLE_USER
            $user->setRoles(['ROLE_USER']);
            $img = rand(0,9);
            $img = 'avatars/'.$img.'.png';
            $user->setUserImage($img);
            $user->setUserEtat(0);
            $user->setUserJouees(0);
            $user->setUserWin(0);
            // On enregistre l'utilisateur dans la base
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $message = (new \Swift_Message('Hello Email'))
                ->setFrom('ribesteban@gmail.com')
                ->setTo($user->getEmail())
                ->setBody('Bienvenue à Halvel Battle');

            $mailer->send($message);

            return $this->redirectToRoute('security_login');
        }

        return $this->render(
            'accueil/inscription.html.twig',
            array('form' => $form->createView())
        );
    }




    /**
     * @Route ("/accueil/connexion", name="connexion")
     */
    public function connexion()
    {
        return $this->render('accueil/connexion.html.twig');
    }

    /**
     * @Route ("/accueil", name="accueil")
     */
    public function accueil()
    {
        return $this->render('accueil/accueil.html.twig');
    }
}
?>