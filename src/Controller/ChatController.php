<?php
namespace App\Controller;


use App\Entity\User;
use App\Entity\Chat;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class ChatController extends Controller
{
    /**
     * @Route("/chat", name="chat")
     */
    public function chat(){
        $joueur = $this->getUser();

        $joueurs = $this->getDoctrine()->getRepository("App:User");
        $joueurs = $joueurs->findAll();

        $limit = $this->getDoctrine()->getRepository("App:Chat");
        $limit = $limit->findBy(array(), array('id' => 'desc'),1,0);
        $limit = $limit[0]->getId();
        $limit = $limit - 5;

        $chat = $this->getDoctrine()->getRepository("App:Chat");
        $chat = $chat->findBy(array(), array('id' => 'ASC'),5, $limit);

        return $this->render("chat.html.twig", ['joueur' => $joueur, 'joueurs' => $joueurs, 'chat'=> $chat]);
    }

    /**
     * @Route("/message", name="new_message")
     */
    public function new_message(Request $request){
        $joueur = $this->getUser();
        $new_message = $request->request->get('message');
        $chat = new Chat();
        $chat->setPseudoId($joueur->getId());
        $chat->setMessageGlobal($new_message);

        $em = $this->getDoctrine()->getManager();
        //Sauvegarde mon objet Chat dans la base de données
        $em->persist($chat);
        $em->flush();
    }

}
?>