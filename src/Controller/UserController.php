<?php
namespace App\Controller;


use App\Entity\User;
use App\Entity\Amis;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class UserController extends Controller
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }


    /**
     * @Route("/user", name="user")
     */
    public function user()
    {
        $joueur = $this->getUser();
        return $this->render('User/index.html.twig', ['joueur' => $joueur]);
    }

    /**
     * @Route("/user/profil", name="profil")
     */
    public function profil()
    {
        $joueur = $this->getUser();

        $joueurs = $this->getDoctrine()->getRepository("App:User");
        $joueurs = $joueurs->findAll();

        $amis = $this->getDoctrine()->getRepository("App:Amis");
        $amis = $amis->findAll();

        $rang = $this->getDoctrine()->getRepository("App:User");
        $rang = $rang->findBy(array(), array('user_win' => 'desc'),5,0);
        return $this->render('User/profil.html.twig', ['joueur' => $joueur, 'joueurs' => $joueurs, 'rang' => $rang, 'amis' => $amis]);
    }

    /**
     * @Route("/user/modifier/avatar", name="modifier_avatar")
     */
    public function modifier_avatar()
    {
        $joueur = $this->getUser();
        return $this->render('User/modifier_avatar.html.twig', ['joueur' => $joueur]);
    }

    /**
     * @Route("/update_avatar", name="new_avatar")
     */
    public function new_avatar(Request $request)
    {
        $new_avatar = $request->request->get('avatar');
        $user = $this->getUser();
        if($user) {
            $id = $user->getId();
        } else {
            $id = "Pas d'Id";
        }
        $entityManager = $this->getDoctrine()->getManager();
        $user_image = $entityManager->getRepository(User::class)->find($id);
        if (!$user_image) {
            throw $this->createNotFoundException(
                'No avatar found for id '.$id
            );
        }
        $user_image->setUserImage($new_avatar);
        $entityManager->persist($user_image);
        $entityManager->flush();
        return $this->redirectToRoute('profil');
    }

    /**
     * @Route("/user/modifier/informations", name="modifier_informations")
     */
    public function modifier_informations()
    {
        $joueur = $this->getUser();

        $joueurs = $this->getDoctrine()->getRepository("App:User");
        $joueurs = $joueurs->findAll();

        $limit = $this->getDoctrine()->getRepository("App:Chat");
        $limit = $limit->findBy(array(), array('id' => 'desc'),1,0);
        $limit = $limit[0]->getId();
        $limit = $limit - 5;

        $chat = $this->getDoctrine()->getRepository("App:Chat");
        $chat = $chat->findBy(array(), array('id' => 'ASC'),5, $limit);

        return $this->render('User/modifier.html.twig', ['joueur' => $joueur, 'joueurs' => $joueurs, 'chat'=> $chat]);
    }

    /**
     * @Route("/update_username", name="update_username")
     */
    public function new_username(Request $request){
        $new_username = $request->request->get('username');
        $user = $this->getUser();
        if($user) {
            $id = $user->getId();
        } else {
            $id = "Pas d'Id";
        }
        $entityManager = $this->getDoctrine()->getManager();
        $username = $entityManager->getRepository(User::class)->find($id);
        if (!$username) {
            throw $this->createNotFoundException(
                'No username found for id '.$id
            );
        }
        $username->setUsername($new_username);
        $entityManager->persist($username);
        $entityManager->flush();
        return $this->redirectToRoute('profil');
    }
    /**
     * @Route("/update_email", name="update_email")
     */
    public function new_email(Request $request){
        $new_email = $request->request->get('email');
        $user = $this->getUser();
        if($user) {
            $id = $user->getId();
        } else {
            $id = "Pas d'Id";
        }
        $entityManager = $this->getDoctrine()->getManager();
        $email = $entityManager->getRepository(User::class)->find($id);
        if (!$email) {
            throw $this->createNotFoundException(
                'No email found for id '.$id
            );
        }
        $email->setEmail($new_email);
        $entityManager->persist($email);
        $entityManager->flush();
        return $this->redirectToRoute('profil');
    }

    /**
     * @Route("/update_password", name="update_password")
     */
    public function new_password(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $password_send = $request->request->get('premier');
        $password_send1 = $request->request->get('deuxieme');

        if ($password_send != $password_send1) {
            return $this->redirectToRoute('user_fail');
        }
        else{
            $user = $this->getUser();
            if ($user) {
                $id = $user->getId();
            } else {
                $id = "Pas d'Id";
            }
            $new_password = $passwordEncoder->encodePassword($user, $password_send);
            $entityManager = $this->getDoctrine()->getManager();
            $password = $entityManager->getRepository(User::class)->find($id);
            if (!$password) {
                throw $this->createNotFoundException(
                    'No username found for id ' . $id
                );
            }
            $password->setPassword($new_password);
            $entityManager->persist($password);
            $entityManager->flush();
            return $this->redirectToRoute('profil');
        }
    }

    /**
     * @Route("/user/rechercher", name="rechercher_joueur")
     */
    public function rechercher_joueur(){

    }
    /**
     * @Route("/user/joueur/{id}", name="joueur")
     */
    public function joueur($id){
        $joueur = $this->getUser();
        if($id != $joueur->getId()){
            $joueurs = $this->getDoctrine()->getRepository("App:User");
            $joueurs = $joueurs->findAll();

            $amis = $this->getDoctrine()->getRepository("App:Amis");
            $amis = $amis->findBy(['id_ami1' => $joueur->getId()]);

            $limit = $this->getDoctrine()->getRepository("App:Chat");
            $limit = $limit->findBy(array(), array('id' => 'desc'),1,0);
            $limit = $limit[0]->getId();
            $limit = $limit - 5;

            $chat = $this->getDoctrine()->getRepository("App:Chat");
            $chat = $chat->findBy(array(), array('id' => 'ASC'),5, $limit);

            return $this->render('User/joueur.html.twig', ['joueur' => $joueur, 'joueurs' => $joueurs, 'id' => $id, 'amis' => $amis, 'chat' => $chat]);
        }
        else{
            return $this->redirectToRoute('profil');
        }
    }
    /**
     * @Route("/user/ajouterami/{id}", name="ajouterami")
     */
    public function ajouterami($id){
        $joueur = $this->getUser();

        $amitie = new Amis();
        $amitie->setIdAmi1($joueur->getId());
        $amitie->setIdAmi2($id);
        $em = $this->getDoctrine()->getManager();
        $em->persist($amitie);
        $em->flush();

        $amitie = new Amis();
        $amitie->setIdAmi1($id);
        $amitie->setIdAmi2($joueur->getId());
        $em = $this->getDoctrine()->getManager();
        $em->persist($amitie);
        $em->flush();

        return $this->redirectToRoute('joueur', ['id' => $id]);
    }

    /**
     * @Route("/user/supprimerami/{id}", name="supprimerami")
     */
    public function supprimerami($id){
        $joueur = $this->getUser();

        $id = (int)$id;
        $amitie = $this->getDoctrine()->getRepository("App:Amis");
        $amitie = $amitie->findOneBy(['id_ami1' => $joueur->getId(), 'id_ami2' => $id]);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($amitie);
        $entityManager->flush();

        $amitie = $this->getDoctrine()->getRepository("App:Amis");
        $amitie = $amitie->findOneBy(['id_ami1' => $id, 'id_ami2' => $joueur->getId()]);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($amitie);
        $entityManager->flush();


        return $this->redirectToRoute('joueur', ['id' => $id]);
    }
}
?>