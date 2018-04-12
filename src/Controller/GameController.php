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
 * @Route("user")
 */
class GameController extends Controller
{
    /**
     * @Route("/partie/nouvelle_partie", name="nouvelle_partie")
     */
    public function nouvellePartie()
    {
        $user = $this->getUser();
        $nbpartie = $user->getUserJouees();
        $joueurs = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('game/nouvelle.html.twig', ['joueurs' => $joueurs, 'nbpartie'=>$nbpartie]);
    }

    /**
     * @Route("/partie/chercher_parties/{idjoueur}", name="chercher_parties")
     */
    public function chercherPartie($idjoueur)
    {
        $partiesjoueurs=$this->getDoctrine()->getRepository("App:Partie")->findPartiesProposition($idjoueur);
        return $this->render('game/chercher_parties.html.twig', ['parties' => $partiesjoueurs]);
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
        $partie->setVainqueur(0);
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
     * @Route("/partie/creer2/{idadversaire}", name="creer2_partie")
     */
    public function creer2Partie($idadversaire) {
        $user = $this->getUser();
        if($user) {
            $id = $user->getId();
        } else {
            $id = "Pas d'Id";
        }

        $joueur = $this->getDoctrine()->getRepository(User::class)->find($id);
        $adversaire = $this->getDoctrine()->getRepository(User::class)->find($idadversaire);

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
        $partie->setVainqueur(0);
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
     * @Route("/partie/afficher/{id}", name="afficher_partie")
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

        //recuperation longueur pioche
        $lenght = $partie->getPioche();
        $lenght = count($lenght);




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
                'joueur'=>$joueur,
                'pioche'=>$lenght
            ]
        );
    }

    /**
     * @Route("/objectifs/{idpartie}", name="objectifs")
     */
    public function objectifs($idpartie){
        $entityManager = $this->getDoctrine()->getManager();
        $partie = $entityManager->getRepository("App:Partie")->find($idpartie);


        $actionJ1 = $partie->getActionJ1();
        $cartesJ1 = array();
        $disparitionJ1 = $actionJ1->disparition;
        $concurrenceJ1 = $actionJ1->concurrence;
        $cadeauJ1 = $actionJ1->cadeau;
        $concurrenceAJ1 = $actionJ1->concurrenceA;
        $cadeauAJ1 = $actionJ1->cadeauA;
        if($disparitionJ1->etat == 1){
            $cartesJ1[] = $disparitionJ1->carte;
        }
        else{
            $cartesJ1[] = 0;
        }
        if($concurrenceJ1->etat == 1){
            $cartesJ1[] = $concurrenceJ1->carte[0];
            $cartesJ1[] = $concurrenceJ1->carte[1];
        }
        else{
            $cartesJ1[] = 0;
            $cartesJ1[] = 0;
        }
        if($cadeauJ1->etat == 0){
            $cartesJ1[] = $cadeauJ1->carte[0];
            $cartesJ1[] = $cadeauJ1->carte[1];
        }
        else{
            $cartesJ1[] = 0;
            $cartesJ1[] = 0;
        }


        $pointJ1 = array();
        if($cartesJ1[0] != 0){
            $point1J1 = $entityManager->getRepository("App:Objet")->find($cartesJ1[0]);
            $pointJ1[] = $point1J1->getValeur();
        }
        if($cartesJ1[1] != 0){
            $point1J1 = $entityManager->getRepository("App:Objet")->find($cartesJ1[1]);
            $pointJ1[] = $point1J1->getValeur();
        }
        if($cartesJ1[2] != 0){
            $point1J1 = $entityManager->getRepository("App:Objet")->find($cartesJ1[2]);
            $pointJ1[] = $point1J1->getValeur();
        }
        if($cartesJ1[3] != 0){
            $point1J1 = $entityManager->getRepository("App:Objet")->find($cartesJ1[3]);
            $pointJ1[] = $point1J1->getValeur();
        }
        if($cartesJ1[4] != 0){
            $point1J1 = $entityManager->getRepository("App:Objet")->find($cartesJ1[4]);
            $pointJ1[] = $point1J1->getValeur();
        }

        sort($pointJ1);
        $taille = sizeof($pointJ1);

        //récupération des cartes objectifs et de leurs attributions
        $objectifs = $this->getDoctrine()->getRepository(Objectifs::class)->findAll();
        $attribution = $partie->getObjectifAttribution();

        return $this->render("game/objectifs.html.twig",
            [
                'joueur1' => $partie->getJ1(),
                'joueur2' => $partie->getJ2(),
                'objectifs'=>$objectifs,
                'attribution'=>$attribution,
                'pointJ1'=>$pointJ1,
                'taille'=> $taille]);
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


        //recuperation longueur pioche
        $lenght = $partie->getPioche();
        $lenght = count($lenght);


        return $this->render("game/actiondispo.html.twig",
            [
                'partie'=>$partie,
                'action_dispo' => $tActionDispo,
                'joueur1' => $partie->getJ1(),
                'joueur2' => $partie->getJ2(),
                'joueur'=>$joueur,
                'idpartie'=>$idpartie,
                'main' => $cartes_main,
                'concurrenceJ1'=>$concurrenceJ1,
                'concurrenceJ2'=>$concurrenceJ2,
                'cadeauJ1'=>$cadeauJ1,
                'cadeauJ2'=>$cadeauJ2,
                'pioche'=>$lenght]);
    }

    /**
     * @Route("/finpartie/{idpartie}", name="finpartie")
     */
    public function finpartie($idpartie){
        $entityManager = $this->getDoctrine()->getManager();
        $partie = $entityManager->getRepository("App:Partie")->find($idpartie);

        $mainJ1 = $partie->getMainJ1();
        $mainJ2 = $partie->getMainJ2();
        $pioche = $partie->getPioche();

        $mainJ1 = count($mainJ1);
        $mainJ2 = count($mainJ2);
        $pioche = count($pioche);

        if($mainJ1==0 && $mainJ2==0 && $pioche==0){
            $actionJ1 = $partie->getActionJ1();
            $cartesJ1 = array();
            $disparitionJ1 = $actionJ1->disparition;
            $concurrenceJ1 = $actionJ1->concurrence;
            $cadeauJ1 = $actionJ1->cadeau;
            $concurrenceAJ1 = $actionJ1->concurrenceA;
            $cadeauAJ1 = $actionJ1->cadeauA;

            $cartesJ1[] = $disparitionJ1->carte;
            $cartesJ1[] = $concurrenceJ1->carte[0];
            $cartesJ1[] = $concurrenceJ1->carte[1];
            $cartesJ1[] = $cadeauJ1->carte[0];
            $cartesJ1[] = $cadeauJ1->carte[1];
            $cartesJ1[] = $concurrenceAJ1->carte[0];
            $cartesJ1[] = $concurrenceAJ1->carte[1];
            $cartesJ1[] = $cadeauAJ1->carte;
            $pointJ1 = array();
            $point1J1 = $entityManager->getRepository("App:Objet")->find($cartesJ1[0]);
            $pointJ1[] = $point1J1->getValeur();
            $point1J1 = $entityManager->getRepository("App:Objet")->find($cartesJ1[1]);
            $pointJ1[] = $point1J1->getValeur();
            $point1J1 = $entityManager->getRepository("App:Objet")->find($cartesJ1[2]);
            $pointJ1[] = $point1J1->getValeur();
            $point1J1 = $entityManager->getRepository("App:Objet")->find($cartesJ1[3]);
            $pointJ1[] = $point1J1->getValeur();
            $point1J1 = $entityManager->getRepository("App:Objet")->find($cartesJ1[4]);
            $pointJ1[] = $point1J1->getValeur();
            $point1J1 = $entityManager->getRepository("App:Objet")->find($cartesJ1[5]);
            $pointJ1[] = $point1J1->getValeur();
            $point1J1 = $entityManager->getRepository("App:Objet")->find($cartesJ1[6]);
            $pointJ1[] = $point1J1->getValeur();
            $point1J1 = $entityManager->getRepository("App:Objet")->find($cartesJ1[7]);
            $pointJ1[] = $point1J1->getValeur();

            $actionJ2 = $partie->getActionJ2();
            $cartesJ2 = array();
            $disparitionJ2 = $actionJ2->disparition;
            $concurrenceJ2 = $actionJ2->concurrence;
            $cadeauJ2 = $actionJ2->cadeau;
            $concurrenceAJ2 = $actionJ2->concurrenceA;
            $cadeauAJ2 = $actionJ2->cadeauA;

            $cartesJ2[] = $disparitionJ2->carte;
            $cartesJ2[] = $concurrenceJ2->carte[0];
            $cartesJ2[] = $concurrenceJ2->carte[1];
            $cartesJ2[] = $cadeauJ2->carte[0];
            $cartesJ2[] = $cadeauJ2->carte[1];
            $cartesJ2[] = $concurrenceAJ2->carte[0];
            $cartesJ2[] = $concurrenceAJ2->carte[1];
            $cartesJ2[] = $cadeauAJ2->carte;
            $pointJ2 = array();
            $point1J2 = $entityManager->getRepository("App:Objet")->find($cartesJ2[0]);
            $pointJ2[] = $point1J2->getValeur();
            $point1J2 = $entityManager->getRepository("App:Objet")->find($cartesJ2[1]);
            $pointJ2[] = $point1J2->getValeur();
            $point1J2 = $entityManager->getRepository("App:Objet")->find($cartesJ2[2]);
            $pointJ2[] = $point1J2->getValeur();
            $point1J2 = $entityManager->getRepository("App:Objet")->find($cartesJ2[3]);
            $pointJ2[] = $point1J2->getValeur();
            $point1J2 = $entityManager->getRepository("App:Objet")->find($cartesJ2[4]);
            $pointJ2[] = $point1J2->getValeur();
            $point1J2 = $entityManager->getRepository("App:Objet")->find($cartesJ2[5]);
            $pointJ2[] = $point1J2->getValeur();
            $point1J2 = $entityManager->getRepository("App:Objet")->find($cartesJ2[6]);
            $pointJ2[] = $point1J2->getValeur();
            $point1J2 = $entityManager->getRepository("App:Objet")->find($cartesJ2[7]);
            $pointJ2[] = $point1J2->getValeur();


            $objectifJ1 = [0,0,0,0,0,0,0];
            $objectifJ2 = [0,0,0,0,0,0,0];
            $attribution = [0,0,0,0,0,0,0];

            for($j=0;$j<7;$j++){
                for($i=0;$i<8;$i++){
                    if($pointJ1[$i] == $j+1){
                        $objectifJ1[$j]++;
                    }
                }
            }
            for($j=0;$j<7;$j++){
                for($i=0;$i<8;$i++){
                    if($pointJ2[$i] == $j+1){
                        $objectifJ2[$j]++;
                    }
                }
            }
            $creatureJ1=0;
            $creatureJ2=0;
            $attributionActuelle = $partie->getObjectifAttribution();

            for($k=0;$k<7;$k++){
                if($objectifJ1[$k] > $objectifJ2[$k]){
                    $creatureJ1++;
                    $attribution[$k] = 1;
                }
                elseif($objectifJ1[$k] < $objectifJ2[$k]){
                    $attribution[$k] = 2;
                    $creatureJ2++;
                }
                elseif($objectifJ1[$k] == $objectifJ2[$k]){
                    $attribution[$k] = $attributionActuelle[$k];
                }
            }
            $partie->setObjectifAttribution(json_encode($attribution));

            $scoreJ1 =0;
            if ($attribution[0]==1){
                $scoreJ1+=2;
            }
            if ($attribution[1]==1){
                $scoreJ1+=2;
            }
            if ($attribution[2]==1){
                $scoreJ1+=2;
            }
            if ($attribution[3]==1){
                $scoreJ1+=3;
            }
            if ($attribution[4]==1){
                $scoreJ1+=3;
            }
            if ($attribution[5]==1){
                $scoreJ1+=4;
            }
            if ($attribution[6]==1){
                $scoreJ1+=5;
            }

            $scoreJ2 =0;
            if ($attribution[0]==2){
                $scoreJ2+=2;
            }
            if ($attribution[1]==2){
                $scoreJ2+=2;
            }
            if ($attribution[2]==2){
                $scoreJ2+=2;
            }
            if ($attribution[3]==2){
                $scoreJ2+=3;
            }
            if ($attribution[4]==2){
                $scoreJ2+=3;
            }
            if ($attribution[5]==2){
                $scoreJ2+=4;
            }
            if ($attribution[6]==2){
                $scoreJ2+=5;
            }

            $vainqueur = 0;
            if($creatureJ1>=4 || $scoreJ1>=11){
                $vainqueur=1;
            }
            elseif($creatureJ2>=4 || $scoreJ2>=11){
                $vainqueur=2;
            }
            else{
                $vainqueur = 0;
            }

            $partie->setVainqueur($vainqueur);
            $partie->setScoreJ1($scoreJ1);
            $partie->setScoreJ2($scoreJ2);

            $em = $this->getDoctrine()->getManager();
            $em->persist($partie);
            $em->flush();
        }
        else{
            $vainqueur = 0;
        }

        return $this->render("game/finpartie.html.twig",
            [
                'idpartie'=>$idpartie,
                'joueur1' => $partie->getJ1(),
                'joueur2' => $partie->getJ2(),
                'vainqueur'=>$vainqueur,
                'mainJ1'=>$mainJ1,
                'mainJ2'=>$mainJ2,
                'pioche'=>$pioche]);
    }
    /**
     * @Route("/traitement_finpartie/{idjoueur}/{vainqueur}", name="traitement_finpartie")
     */
    public function traitement_finpartie($idjoueur, $vainqueur){
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository("App:User")->find($idjoueur);
        if($vainqueur == 'oui'){
            $jouees = $user->getUserJouees();
            $win = $user->getUserWin();
            $jouees++;
            $win++;
            $user->setUserJouees($jouees);
            $user->setUserWin($win);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }
        elseif($vainqueur == 'non'){
            $jouees = $user->getUserJouees();
            $jouees++;
            $user->setUserJouees($jouees);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }
        return $this->redirectToRoute('nouvelle_partie');
    }

    /**
     * @Route("/manche_suivante/{idpartie}", name="manche_suivante")
     */
    public function manche_suivante($idpartie){
        $entityManager = $this->getDoctrine()->getManager();
        $partie = $entityManager->getRepository("App:Partie")->find($idpartie);
        //manche
        $manche = $partie->getManche();
        $manche++;
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

        //créer un objet de type Partie

        $partie->setCarteEcartee(json_encode($carte_ecartee));
        $partie->setMainJ1(json_encode($tMainJ1));
        $partie->setMainJ2(json_encode($tMainJ2));
        $partie->setPioche(json_encode($tPioche));
        $partie->setTour('j1');
        $partie->setManche($manche);
        $partie->setActionJ1(json_encode($tAction));
        $partie->setActionJ2(json_encode($tAction));

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
}