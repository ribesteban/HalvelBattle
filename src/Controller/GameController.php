<?php
namespace App\Controller;
use App\Entity\User;
use App\Entity\Objet;
use App\Entity\Objectifs;
use App\Entity\Partie;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
/**
 * Class GameController
 * @package App\Controller
 */
class GameController extends Controller
{
    /**
     * @Route("/partie/nouvelle_partie", name="nouvelle_partie")
     */
    public function nouvellePartie()
    {
        $joueurs = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('game/nouvelle.html.twig', ['joueurs' => $joueurs]);
    }
    /**
     * @Route("/partie/creer", name="creer_partie")
     */
    public function creerPartie(Request $request) {
        $user = $this->getUser();
        if($user) {
            $id = $user->getId();
        } else {
            $id = "Pas d'Id";
        }
        $idAdversaire = $request->request->get('adversaire');
        $joueur = $this->getDoctrine()->getRepository(User::class)->find($id);
        $adversaire = $this->getDoctrine()->getRepository(User::class)->find($idAdversaire);
        $nom= $request->request->get('nom_partie');
        //récupérer les cartes depuis la base de données et mélanger leur id
        $cartes = $this->getDoctrine()->getRepository(Objet::class)->findBy(array(), ['id' => 'ASC'], 21);
        $carteplus = $this->getDoctrine()->getRepository(Objet::class)->findBy(array(), ['id' => 'DESC'], 1);
        $tCartes = array();
        foreach ($cartes as $carte) {
            $tCartes[] = $carte->getId();
        }
        shuffle($tCartes);
        //retrait de la première carte
        $carte_ecartee = array_pop($tCartes);
        //Distribution des cartes aux joueurs,
        $tMainJ1 = array();
        for($i=0; $i<7; $i++) {
            $tMainJ1[] = array_pop($tCartes);
        }
        $tMainJ2 = array();
        for($i=0; $i<6; $i++) {
            $tMainJ2[] = array_pop($tCartes);
        }
        $tMainJ1[] = 22;
        $tMainJ2[] = 22;
        //La création de la pioche ,sauvegarde des dernières cartes dans la pioche
        $tPioche = $tCartes;
        // actions au départ
        $dissimulation = array('etat'=>0, 'carte'=>0);
        $disparition = array('etat'=>0, 'carte'=>array());
        $cadeau = array('etat'=>0, 'carte'=>array());
        $cadeauA = array('etat'=>1, 'carte'=>0);
        $concurrence = array('etat'=>0, 'carte'=>array());
        $concurrenceA = array('etat'=>1, 'carte'=>array());// A revoir
        // tableau de toutes les actions
        $tAction = array("dissimulation"=>$dissimulation, "disparition"=>$disparition, "cadeau"=>$cadeau, "cadeauA"=>$cadeauA, "concurrence"=>$concurrence, "concurrenceA"=>$concurrenceA);
        // attribution des objectif par id à j1 et j2
        $tObjectifs_attribution = array(0,0,0,0,0,0,0);
        //créer un objet de type Partie
        $partie = new Partie();
        $partie->setJ1($joueur);
        $partie->setJ2($adversaire);
        $partie->setCarteEcartee(json_encode($carte_ecartee));
        $partie->setMainJ1(json_encode($tMainJ1));
        $partie->setMainJ2(json_encode($tMainJ2));
        $partie->setPioche(json_encode($tPioche));
        $partie->setTour('j1');
        $partie->setManche(1);
        $partie->setActionJ1(json_encode($tAction));
        $partie->setActionJ2(json_encode($tAction));
        $partie->setObjectifAttribution(json_encode($tObjectifs_attribution));
        $partie->setScoreJ1(0);
        $partie->setScoreJ2(0);
        //Sauvegarde mon objet Partie dans la base de données et redirection vers l'affichage
        $em = $this->getDoctrine()->getManager();
        $em->persist($partie);
        $em->flush();
        //Je recupère les mains :
        $entityManager = $this->getDoctrine()->getManager();
        $partie = $entityManager->getRepository("App:Partie")->find($partie->getId());

        $mainJ1 = $partie->getMainJ1();
        foreach ($mainJ1 as  $key=>$value) {
            if($value == 22){
                unset($mainJ1[$key]);
            }
        }
        $mainJ2 = $partie->getMainJ2();
        foreach ($mainJ2 as  $key=>$value) {
            if($value == 22){
                unset($mainJ2[$key]);
            }
        }
        $partie->setMainJ1(json_encode($mainJ1));
        $partie->setMainJ2(json_encode($mainJ2));
        $em = $this->getDoctrine()->getManager();
        $em->persist($partie);
        $em->flush();
        return $this->redirectToRoute('afficher_partie', ['id' => $partie->getId(), 'partie'=>$partie]);
    }
    /**
     * @Route("/afficher/{id}", name="afficher_partie")
     */
    public function afficherPartie(Partie $partie) {
        //récupération du joueur à partir de la session
        $user = $this->getUser();
        $idjoueur = $user->getId();
        $idjoueur1 = $partie->getJ1();
        $idjoueur1 = $idjoueur1->getId();
        if($idjoueur == $idjoueur1){
            $joueur = 'j1';
        }else{
            $joueur = 'j2';
        }
        //récupération de sa main et de ses actions dispos
        if($joueur=='j1'){
            $main = $partie->getMainJ1();
            $action= $partie->getActionJ1();
        }else{
            $main = $partie->getMainJ2();
            $action = $partie->getActionJ2();
        }
        //afficher actions du joueur à qui c'est le tour

        $tour = $partie->getTour();
        if($tour == $joueur){
            $tActionDispo = array();
            $tActionIndispo = array();
            foreach ($action as $key=>$value){
                foreach ($value as $key2=>$value2){
                    if(($key2=='etat' && $value2==0)){
                        $tActionDispo [] =  $key;
                    }
                    elseif($key=='etat' && $value2==1){
                        $tActionIndispo [] =  $key;
                        print_r($tActionIndispo);
                    }
                }
            }
        }else{
            $tActionDispo = 0;
            $tActionIndispo = 0;
        }

        //récupération des cartes de la main du joueur par nom, id et valeur
        $cartes_main = array();
        foreach ($main as $id) {
            if($id !=0){
                $carte = $this->getDoctrine()->getRepository(Objet::class)->find($id);
                $cartes_main[] = ['nom'=>$carte->getNom(), 'id'=>$id, 'valeur'=>$carte->getValeur(), 'img'=>$carte->getObjetImg()];
            }
        }
        //récupération des cartes objectifs et de leurs attributions
        $objectifs = $this->getDoctrine()->getRepository(Objectifs::class)->findAll();
        $attribution = $partie->getObjectifAttribution();

        //récupération etat action concurrence et cadeau
        $actionJ1 = $partie->getActionJ1();
        $concurrenceJ1 = $actionJ1->concurrence;
        $concurrenceJ1 = $concurrenceJ1->etat;
        $cadeauJ1 = $actionJ1->cadeau;
        $cadeauJ1 = $cadeauJ1->etat;

        $actionJ2 = $partie->getActionJ2();
        $concurrenceJ2 = $actionJ2->concurrence;
        $concurrenceJ2 = $concurrenceJ2->etat;
        $cadeauJ2 = $actionJ2->cadeau;
        $cadeauJ2 = $cadeauJ2->etat;




        //affiche le plateau
        return $this->render('game/afficher.html.twig',
            [
                'partie' => $partie,
                'joueur1' => $partie->getJ1(),
                'joueur2' => $partie->getJ2(),
                'idpartie' => $partie->getId(),
                'main'=> $cartes_main,
                'objectifs' => $objectifs,
                'attribution' => $attribution,
                'action_dispo'=> $tActionDispo,
                'action_indispo'=> $tActionIndispo,
                'concurrenceJ1'=>$concurrenceJ1,
                'concurrenceJ2'=>$concurrenceJ2,
                'cadeauJ1'=>$cadeauJ1,
                'cadeauJ2'=>$cadeauJ2,
                'joueur'=>$joueur
            ]
        );
    }

    /**
     * @Route("/affiche_actiondispo/{idpartie}/{joueur}", name="afficher_actiondispo")
     */
    public function afficher_actiondispo($idpartie, $joueur){
        $entityManager = $this->getDoctrine()->getManager();
        $partie = $entityManager->getRepository("App:Partie")->find($idpartie);
        $tour = $partie->getTour();
        if($joueur=='j1'){
            $main = $partie->getMainJ1();
            $action= $partie->getActionJ1();
            $action_autre = $partie->getActionJ2();
        }else{
            $main = $partie->getMainJ2();
            $action = $partie->getActionJ2();
            $action_autre = $partie->getActionJ1();
        }
        if($tour == $joueur){
            $tActionDispo = array();
            $tActionIndispo = array();
            foreach ($action as $key=>$value){
                foreach ($value as $key2=>$value2){
                    if(($key2=='etat' && $value2==0)){
                        $tActionDispo [] =  $key;
                    }
                    elseif($key=='etat' && $value2==1){
                        $tActionIndispo [] =  $key;
                        print_r($tActionIndispo);
                    }
                }
            }
        }else{
            $tActionDispo = 0;
            $tActionIndispo = 0;
        }
        $cartes_main = array();
        foreach ($main as $id) {
            if($id !=0){
                $carte = $this->getDoctrine()->getRepository(Objet::class)->find($id);
                $cartes_main[] = ['nom'=>$carte->getNom(), 'id'=>$id, 'valeur'=>$carte->getValeur(), 'img'=>$carte->getObjetImg()];
            }
        }
        return $this->render("game/actiondispo.html.twig", ['action_dispo' => $tActionDispo, 'joueur1' => $partie->getJ1(), 'joueur2' => $partie->getJ2(),'joueur'=>$joueur, 'idpartie'=>$idpartie, 'main' => $cartes_main]);
    }
}