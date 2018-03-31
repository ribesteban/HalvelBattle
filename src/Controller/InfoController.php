<?php
namespace App\Controller;


use App\Entity\User;
use App\Entity\Chat;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class InfoController extends Controller
{
    /**
     * @Route("/info/regles", name="regles")
     */
    public function regles(){
        $joueur = $this->getUser();

        $joueurs = $this->getDoctrine()->getRepository("App:User");
        $joueurs = $joueurs->findAll();

        $limit = $this->getDoctrine()->getRepository("App:Chat");
        $limit = $limit->findBy(array(), array('id' => 'desc'),1,0);
        $limit = $limit[0]->getId();
        $limit = $limit - 5;

        $chat = $this->getDoctrine()->getRepository("App:Chat");
        $chat = $chat->findBy(array(), array('id' => 'ASC'),5, $limit);

        return $this->render("info/regles.html.twig", ['joueur' => $joueur, 'joueurs' => $joueurs, 'chat'=> $chat]);

    }
    /**
     * @Route("/info/assistance", name="assistance")
     */
    public function assistance(){
        $joueur = $this->getUser();

        $joueurs = $this->getDoctrine()->getRepository("App:User");
        $joueurs = $joueurs->findAll();

        $limit = $this->getDoctrine()->getRepository("App:Chat");
        $limit = $limit->findBy(array(), array('id' => 'desc'),1,0);
        $limit = $limit[0]->getId();
        $limit = $limit - 5;

        $chat = $this->getDoctrine()->getRepository("App:Chat");
        $chat = $chat->findBy(array(), array('id' => 'ASC'),5, $limit);

        return $this->render("info/assistance.html.twig", ['joueur' => $joueur, 'joueurs' => $joueurs, 'chat'=> $chat]);
    }
}
?>