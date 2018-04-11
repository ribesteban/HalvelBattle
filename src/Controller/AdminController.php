<?php
namespace App\Controller;


use App\Entity\User;
use App\Entity\Chat;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function admin(){

        return $this->render("admin/admin.html.twig");

    }
    /**
     * @Route("/admin/partie", name="adminpartie")
     */
    public function adminpartie(){
        $entityManager = $this->getDoctrine()->getManager();
        $parties = $entityManager->getRepository("App:Partie")->findAll();
        return $this->render("admin/adminpartie.html.twig", ["parties"=>$parties]);

    }
    /**
     * @Route("/admin/supprimerpartie/{idpartie}", name="adminsupprimerpartie")
     */
    public function adminsupprimerpartie($idpartie){
        $entityManager = $this->getDoctrine()->getManager();
        $partie = $entityManager->getRepository("App:Partie")->find($idpartie);
        $entityManager->remove($partie);
        $entityManager->flush();
        return $this->redirectToRoute('adminpartie');
    }

    /**
     * @Route("/admin/user", name="adminuser")
     */
    public function adminuser(){
        $entityManager = $this->getDoctrine()->getManager();
        $users = $entityManager->getRepository("App:User")->findAll();
        $amis = $entityManager->getRepository("App:Amis")->findAll();
        return $this->render("admin/adminuser.html.twig", ["users"=>$users, "amis"=>$amis]);
    }

    /**
     * @Route("/admin/supprimeruser/{iduser}", name="adminsupprimeruser")
     */
    public function adminsupprimeruser($iduser){
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository("App:User")->find($iduser);

        $partiesjoueurs=$this->getDoctrine()->getRepository("App:Partie")->findPartiesJoueur($iduser);
        foreach ($partiesjoueurs as $row){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($row);
        }
        $entityManager->remove($user);
        $entityManager->flush();
        return $this->redirectToRoute('adminuser');
    }
}
?>