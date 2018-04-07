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
class ActionController extends Controller
{
    /**
     * @Route("/action/disparition/{carte1}/{joueur}/{idpartie}", name="disparition")
     */
    public function disparition($carte1, $joueur, $idpartie){

        $carte1 = $this->getDoctrine()->getRepository("App:Objet")->find($carte1);

        return $this->render("game/secret.html.twig",['carte1' => $carte1, 'joueur' => $joueur, 'idpartie'=>$idpartie]);
    }


    /**
     * @Route("/action/disparition_traitement/{carte1}/{joueur}/{idpartie}", name="disparition_traitement")
     */
    public function disparition_traitement($carte1, $joueur, $idpartie){

        $carte1 = $this->getDoctrine()->getRepository("App:Objet")->find($carte1);
        $entityManager = $this->getDoctrine()->getManager();
        $partie = $entityManager->getRepository("App:Partie")->find($idpartie);
        //si c'est le joueur1
        if($joueur == 'j1'){
            //je modifie son action dispariotn
            $action = $partie->getActionJ1();
            $disparition = $action->disparition;
            $disparition->etat = 1;
            $disparition->carte = $carte1->getId();
            $action->disparition = $disparition;
            $partie->setActionJ1(json_encode($action));


            $main = $partie->getMainJ1();
            $tmainJ1 = array();
            print_r($main);
            foreach ($main as  $value) {
                if($value != $carte1->getId()){
                    $tmainJ1[] = $value;
                }
            }
            $partie->setMainJ1(json_encode($tmainJ1));

            $pioche = $partie->getPioche();
            $carte_piochee = array_pop($pioche);
            $main = $partie->getMainJ2();
            if($carte_piochee != null){
                $main[] = $carte_piochee;
            }

            $partie->setPioche(json_encode($pioche));
            $partie->setMainJ2(json_encode($main));
            $partie->setTour('j2');
            $em = $this->getDoctrine()->getManager();
            $em->persist($partie);
            $em->flush();
            return $this->redirectToRoute('afficher_partie', ['id' => $idpartie, 'partie'=>$partie]);
        }
        else{
            //je modifie son action dispariotn
            $action = $partie->getActionJ2();
            $disparition = $action->disparition;
            $disparition->etat = 1;
            $disparition->carte = $carte1->getId();
            $action->disparition = $disparition;
            $partie->setActionJ2(json_encode($action));


            $main = $partie->getMainJ2();
            $tmainJ2 = array();
            print_r($main);
            foreach ($main as  $value) {
                if($value != $carte1->getId()){
                    $tmainJ2[] = $value;
                }
            }
            $partie->setMainJ2(json_encode($tmainJ2));

            $pioche = $partie->getPioche();
            $carte_piochee = array_pop($pioche);
            $main = $partie->getMainJ1();
            if($carte_piochee != null){
                $main[] = $carte_piochee;
            }

            $partie->setPioche(json_encode($pioche));
            $partie->setMainJ1(json_encode($main));
            $partie->setTour('j1');
            $em = $this->getDoctrine()->getManager();
            $em->persist($partie);
            $em->flush();
            return $this->redirectToRoute('afficher_partie', ['id' => $idpartie, 'partie'=>$partie]);
        }
    }


    /**
     * @Route("/action/dissimulation/{carte1}/{carte2}/{joueur}/{idpartie}", name="dissimulation")
     */
    public function dissimulation($carte1, $carte2, $joueur, $idpartie){
        $carte1 = $this->getDoctrine()->getRepository("App:Objet")->find($carte1);
        $carte2 = $this->getDoctrine()->getRepository("App:Objet")->find($carte2);
        return $this->render("game/dissimulation.html.twig",['carte1' => $carte1,'carte2' => $carte2, 'joueur' => $joueur, 'idpartie'=>$idpartie]);
    }


    /**
     * @Route("/action/dissimulation_traitement/{carte1}/{carte2}/{joueur}/{idpartie}", name="dissimulation_traitement")
     */
    public function dissimulation_traitement($carte1, $carte2, $joueur, $idpartie){
        $carte1 = $this->getDoctrine()->getRepository("App:Objet")->find($carte1);
        $carte2 = $this->getDoctrine()->getRepository("App:Objet")->find($carte2);
        $entityManager = $this->getDoctrine()->getManager();
        $partie = $entityManager->getRepository("App:Partie")->find($idpartie);
        //si c'est le joueur1
        if($joueur == 'j1'){
            //je modifie son action dispariotn
            $action = $partie->getActionJ1();
            $dissimulation = $action->dissimulation ;
            $dissimulation ->etat = 1;
            $dissimulation ->carte = array($carte1->getId(), $carte2->getId());
            $action->dissimulation = $dissimulation;
            $partie->setActionJ1(json_encode($action));


            $main = $partie->getMainJ1();
            $tmainJ1 = array();
            print_r($main);
            foreach ($main as  $value) {
                if($value != $carte1->getId() && $value != $carte2->getId()){
                    $tmainJ1[] = $value;
                }
            }
            $partie->setMainJ1(json_encode($tmainJ1));

            $pioche = $partie->getPioche();
            $carte_piochee = array_pop($pioche);
            $main = $partie->getMainJ2();
            if($carte_piochee != null){
                $main[] = $carte_piochee;
            }

            $partie->setPioche(json_encode($pioche));
            $partie->setMainJ2(json_encode($main));
            $partie->setTour('j2');
            $em = $this->getDoctrine()->getManager();
            $em->persist($partie);
            $em->flush();
            return $this->redirectToRoute('afficher_partie', ['id' => $idpartie, 'partie'=>$partie]);
        }
        else{
            //je modifie son action dispariotn
            $action = $partie->getActionJ2();
            $dissimulation = $action->dissimulation ;
            $dissimulation ->etat = 1;
            $dissimulation ->carte = array($carte1->getId(), $carte2->getId());
            $action->dissimulation = $dissimulation;
            $partie->setActionJ2(json_encode($action));


            $main = $partie->getMainJ2();
            $tmainJ2 = array();
            print_r($main);
            foreach ($main as  $value) {
                if($value != $carte1->getId() && $value != $carte2->getId()){
                    $tmainJ2[] = $value;
                }
            }
            $partie->setMainJ2(json_encode($tmainJ2));

            $pioche = $partie->getPioche();
            $carte_piochee = array_pop($pioche);
            $main = $partie->getMainJ1();
            if($carte_piochee != null){
                $main[] = $carte_piochee;
            }

            $partie->setPioche(json_encode($pioche));
            $partie->setMainJ1(json_encode($main));
            $partie->setTour('j1');
            $em = $this->getDoctrine()->getManager();
            $em->persist($partie);
            $em->flush();
            return $this->redirectToRoute('afficher_partie', ['id' => $idpartie, 'partie'=>$partie]);
        }
    }

    /**
     * @Route("/action/cadeau/{carte1}/{carte2}/{carte3}/{joueur}/{idpartie}", name="cadeau")
     */
    public function cadeau($carte1, $carte2, $carte3, $joueur, $idpartie){
        $carte1 = $this->getDoctrine()->getRepository("App:Objet")->find($carte1);
        $carte2 = $this->getDoctrine()->getRepository("App:Objet")->find($carte2);
        $carte3 = $this->getDoctrine()->getRepository("App:Objet")->find($carte3);
        return $this->render("game/cadeau.html.twig",['carte1' => $carte1,'carte2' => $carte2, 'carte3' => $carte3, 'joueur' => $joueur, 'idpartie'=>$idpartie]);
    }

    /**
     * @Route("/action/cadeau_suite/{carte1}/{carte2}/{carte3}/{joueur}/{idpartie}", name="cadeau_suite")
     */
    public function cadeau_suite($carte1, $carte2, $carte3, $joueur, $idpartie){
        $carte1 = $this->getDoctrine()->getRepository("App:Objet")->find($carte1);
        $carte2 = $this->getDoctrine()->getRepository("App:Objet")->find($carte2);
        $carte3 = $this->getDoctrine()->getRepository("App:Objet")->find($carte3);
        $entityManager = $this->getDoctrine()->getManager();
        $partie = $entityManager->getRepository("App:Partie")->find($idpartie);
        //si c'est le joueur1
        if($joueur == 'j1'){
            //je modifie son action dispariotn
            $action = $partie->getActionJ1();
            $cadeau = $action->cadeau ;
            $cadeau ->etat = 2;
            $cadeau ->carte = array($carte1->getId(), $carte2->getId(), $carte3->getId());
            $action->cadeau = $cadeau;
            $partie->setActionJ1(json_encode($action));


            $main = $partie->getMainJ1();
            $tmainJ1 = array();
            print_r($main);
            foreach ($main as  $value) {
                if($value != $carte1->getId() && $value != $carte2->getId() && $value != $carte3->getId()){
                    $tmainJ1[] = $value;
                }
            }
            $partie->setMainJ1(json_encode($tmainJ1));

            $partie->setTour('j2');
            $em = $this->getDoctrine()->getManager();
            $em->persist($partie);
            $em->flush();
            return $this->redirectToRoute('afficher_partie', ['id' => $idpartie, 'partie'=>$partie]);
        }
        else{
            //je modifie son action dispariotn
            $action = $partie->getActionJ2();
            $cadeau = $action->cadeau ;
            $cadeau ->etat = 2;
            $cadeau ->carte = array($carte1->getId(), $carte2->getId(), $carte3->getId());
            $action->cadeau = $cadeau;
            $partie->setActionJ2(json_encode($action));


            $main = $partie->getMainJ2();
            $tmainJ2 = array();
            print_r($main);
            foreach ($main as  $value) {
                if($value != $carte1->getId() && $value != $carte2->getId() && $value != $carte3->getId()){
                    $tmainJ2[] = $value;
                }
            }
            $partie->setMainJ2(json_encode($tmainJ2));

            $partie->setTour('j1');
            $em = $this->getDoctrine()->getManager();
            $em->persist($partie);
            $em->flush();
            return $this->redirectToRoute('afficher_partie', ['id' => $idpartie, 'partie'=>$partie]);
        }
    }

    /**
     * @Route("/action/cadeau_suite2/{joueur}/{idpartie}", name="cadeau_suite2")
     */
    public function cadeau_suite2($joueur, $idpartie){
        $entityManager = $this->getDoctrine()->getManager();
        $partie = $entityManager->getRepository("App:Partie")->find($idpartie);
        if($joueur == 'j1'){
            $action = $partie->getActionJ2();
            $cadeau = $action->cadeau;
            if($cadeau->etat == 2){
                $carte1 = $cadeau->carte[0];
                $carte2 = $cadeau->carte[1];
                $carte3 = $cadeau->carte[2];
                $carte1 = $this->getDoctrine()->getRepository("App:Objet")->find($carte1);
                $carte2 = $this->getDoctrine()->getRepository("App:Objet")->find($carte2);
                $carte3 = $this->getDoctrine()->getRepository("App:Objet")->find($carte3);
                return $this->render("game/cadeau_suite2.html.twig", ['carte1' => $carte1,'carte2' => $carte2, 'carte3' => $carte3, 'joueur'=>$joueur, 'idpartie'=>$idpartie]);
            }
            else{
                $carte1 = $this->getDoctrine()->getRepository("App:Objet")->find(22);
                $carte2 = $this->getDoctrine()->getRepository("App:Objet")->find(22);
                $carte3 = $this->getDoctrine()->getRepository("App:Objet")->find(22);
                return $this->render("game/cadeau_suite2.html.twig", ['carte1' => $carte1,'carte2' => $carte2, 'carte3' => $carte3, 'joueur'=>$joueur, 'idpartie'=>$idpartie]);
            }
        }elseif($joueur == 'j2'){
            $action = $partie->getActionJ1();
            $cadeau = $action->cadeau;
            if($cadeau->etat == 2){
                $carte1 = $cadeau->carte[0];
                $carte2 = $cadeau->carte[1];
                $carte3 = $cadeau->carte[2];
                $carte1 = $this->getDoctrine()->getRepository("App:Objet")->find($carte1);
                $carte2 = $this->getDoctrine()->getRepository("App:Objet")->find($carte2);
                $carte3 = $this->getDoctrine()->getRepository("App:Objet")->find($carte3);
                return $this->render("game/cadeau_suite2.html.twig", ['carte1' => $carte1,'carte2' => $carte2, 'carte3' => $carte3, 'joueur'=>$joueur, 'idpartie'=>$idpartie]);
            }
            else{
                $carte1 = $this->getDoctrine()->getRepository("App:Objet")->find(22);
                $carte2 = $this->getDoctrine()->getRepository("App:Objet")->find(22);
                $carte3 = $this->getDoctrine()->getRepository("App:Objet")->find(22);
                return $this->render("game/cadeau_suite2.html.twig", ['carte1' => $carte1,'carte2' => $carte2, 'carte3' => $carte3, 'joueur'=>$joueur, 'idpartie'=>$idpartie]);
            }
        }
    }

    /**
     * @Route("/action/cadeau_traitement/{carte1}/{joueur}/{idpartie}", name="cadeau_traitement")
     */
    public function cadeau_traitement($carte1, $joueur, $idpartie){
        $carte1 = $this->getDoctrine()->getRepository("App:Objet")->find($carte1);
        $entityManager = $this->getDoctrine()->getManager();
        $partie = $entityManager->getRepository("App:Partie")->find($idpartie);
        if($joueur == 'j1'){
            print_r($carte1->getId());
            //je modifie son action dispariotn
            $action = $partie->getActionJ1();
            $cadeauA = $action->cadeauA;
            $cadeauA->carte = $carte1->getId();
            $action->cadeauA = $cadeauA;
            $partie->setActionJ1(json_encode($action));

            $action = $partie->getActionJ2();
            $cadeau = $action->cadeau;
            $cadeau->etat = 1;
            $carte = $cadeau->carte;
            $tcadeau = array();
            foreach ($carte as  $value) {
                if($value != $carte1->getId()){
                    $tcadeau[] = $value;
                }
            }
            print_r($tcadeau);
            $cadeau->carte = $tcadeau;
            $action->cadeau = $cadeau;
            $partie->setActionJ2(json_encode($action));

            $pioche = $partie->getPioche();
            $carte_piochee = array_pop($pioche);
            $main = $partie->getMainJ1();
            if($carte_piochee != null){
                $main[] = $carte_piochee;
            }
            $partie->setMainJ1(json_encode($main));
            $partie->setPioche(json_encode($pioche));

            $em = $this->getDoctrine()->getManager();
            $em->persist($partie);
            $em->flush();
            return $this->redirectToRoute('afficher_partie', ['id' => $idpartie, 'partie'=>$partie]);
        }
        elseif($joueur == 'j2'){
            //je modifie son action dispariotn
            $action = $partie->getActionJ2();
            $cadeauA = $action->cadeauA;
            $cadeauA->carte = $carte1->getId();
            $action->cadeauA = $cadeauA;
            $partie->setActionJ2(json_encode($action));

            $action = $partie->getActionJ1();
            $cadeau = $action->cadeau;
            $cadeau->etat = 1;
            $carte = $cadeau->carte;
            $tcadeau = array();
            foreach ($carte as  $value) {
                if($value != $carte1->getId()){
                    $tcadeau[] = $value;
                }
            }
            print_r($tcadeau);
            $cadeau->carte = $tcadeau;
            $action->cadeau = $cadeau;
            $partie->setActionJ1(json_encode($action));

            $pioche = $partie->getPioche();
            $carte_piochee = array_pop($pioche);
            $main = $partie->getMainJ2();
            if($carte_piochee != null){
                $main[] = $carte_piochee;
            }
            $partie->setMainJ2(json_encode($main));
            $partie->setPioche(json_encode($pioche));

            $em = $this->getDoctrine()->getManager();
            $em->persist($partie);
            $em->flush();
            return $this->redirectToRoute('afficher_partie', ['id' => $idpartie, 'partie'=>$partie]);
        }
    }

    /**
     * @Route("/action/concurrence/{carte1}/{carte2}/{carte3}/{carte4}/{joueur}/{idpartie}", name="concurrence")
     */
    public function concurrence($carte1, $carte2, $carte3, $carte4, $joueur, $idpartie){
        $carte1 = $this->getDoctrine()->getRepository("App:Objet")->find($carte1);
        $carte2 = $this->getDoctrine()->getRepository("App:Objet")->find($carte2);
        $carte3 = $this->getDoctrine()->getRepository("App:Objet")->find($carte3);
        $carte4 = $this->getDoctrine()->getRepository("App:Objet")->find($carte4);
        return $this->render("game/concurrence.html.twig", ['carte1' => $carte1,'carte2' => $carte2, 'carte3' => $carte3, 'carte4'=> $carte4, 'joueur'=>$joueur, 'idpartie'=>$idpartie]);
    }

    /**
     * @Route("/action/concurrence_suite/{carte1}/{carte2}/{carte3}/{carte4}/{joueur}/{idpartie}", name="concurrence_suite")
     */
    public function concurrence_suite($carte1, $carte2, $carte3, $carte4, $joueur, $idpartie){
        $carte1 = $this->getDoctrine()->getRepository("App:Objet")->find($carte1);
        $carte2 = $this->getDoctrine()->getRepository("App:Objet")->find($carte2);
        $carte3 = $this->getDoctrine()->getRepository("App:Objet")->find($carte3);
        $carte4 = $this->getDoctrine()->getRepository("App:Objet")->find($carte4);
        $entityManager = $this->getDoctrine()->getManager();
        $partie = $entityManager->getRepository("App:Partie")->find($idpartie);
        //si c'est le joueur1
        if($joueur == 'j1'){
            //je modifie son action dispariotn
            $action = $partie->getActionJ1();
            $concurrence = $action->concurrence ;
            $concurrence ->etat = 2;
            $concurrence ->carte = array($carte1->getId(), $carte2->getId(), $carte3->getId(), $carte4->getId());
            $action->concurrence = $concurrence;
            $partie->setActionJ1(json_encode($action));


            $main = $partie->getMainJ1();
            $tmainJ1 = array();
            print_r($main);
            foreach ($main as  $value) {
                if($value != $carte1->getId() && $value != $carte2->getId() && $value != $carte3->getId() && $value != $carte4->getId()){
                    $tmainJ1[] = $value;
                }
            }
            $partie->setMainJ1(json_encode($tmainJ1));

            $partie->setTour('j2');
            $em = $this->getDoctrine()->getManager();
            $em->persist($partie);
            $em->flush();
            return $this->redirectToRoute('afficher_partie', ['id' => $idpartie, 'partie'=>$partie]);
        }
        else{
            //je modifie son action dispariotn
            $action = $partie->getActionJ2();
            $concurrence = $action->concurrence ;
            $concurrence ->etat = 2;
            $concurrence ->carte = array($carte1->getId(), $carte2->getId(), $carte3->getId(), $carte4->getId());
            $action->concurrence = $concurrence;
            $partie->setActionJ2(json_encode($action));


            $main = $partie->getMainJ2();
            $tmainJ2 = array();
            print_r($main);
            foreach ($main as  $value) {
                if($value != $carte1->getId() && $value != $carte2->getId() && $value != $carte3->getId() && $value != $carte4->getId()){
                    $tmainJ2[] = $value;
                }
            }
            $partie->setMainJ2(json_encode($tmainJ2));

            $partie->setTour('j1');
            $em = $this->getDoctrine()->getManager();
            $em->persist($partie);
            $em->flush();
            return $this->redirectToRoute('afficher_partie', ['id' => $idpartie, 'partie'=>$partie]);
        }
    }

    /**
     * @Route("/action/concurrence_suite2/{joueur}/{idpartie}", name="concurrence_suite2")
     */
   public function concurrence_suite2($joueur, $idpartie){
        $entityManager = $this->getDoctrine()->getManager();
        $partie = $entityManager->getRepository("App:Partie")->find($idpartie);
        if($joueur == 'j1'){
            $action = $partie->getActionJ2();
            $concurrence = $action->concurrence;
            if($concurrence->etat == 2){
                $carte1 = $concurrence->carte[0];
                $carte2 = $concurrence->carte[1];
                $carte3 = $concurrence->carte[2];
                $carte4 = $concurrence->carte[3];
                $carte1 = $this->getDoctrine()->getRepository("App:Objet")->find($carte1);
                $carte2 = $this->getDoctrine()->getRepository("App:Objet")->find($carte2);
                $carte3 = $this->getDoctrine()->getRepository("App:Objet")->find($carte3);
                $carte4 = $this->getDoctrine()->getRepository("App:Objet")->find($carte4);
                return $this->render("game/concurrence_suite2.html.twig", ['carte1' => $carte1,'carte2' => $carte2, 'carte3' => $carte3, 'carte4'=>$carte4, 'joueur'=>$joueur, 'idpartie'=>$idpartie]);
            }
            else{
                $carte1 = $this->getDoctrine()->getRepository("App:Objet")->find(22);
                $carte2 = $this->getDoctrine()->getRepository("App:Objet")->find(22);
                $carte3 = $this->getDoctrine()->getRepository("App:Objet")->find(22);
                $carte4 = $this->getDoctrine()->getRepository("App:Objet")->find(22);
                return $this->render("game/concurrence_suite2.html.twig", ['carte1' => $carte1,'carte2' => $carte2, 'carte3' => $carte3, 'carte4'=>$carte4, 'joueur'=>$joueur, 'idpartie'=>$idpartie]);
            }
        }elseif($joueur == 'j2'){
            $action = $partie->getActionJ1();
            $concurrence = $action->concurrence;
            if($concurrence->etat == 2){
                $carte1 = $concurrence->carte[0];
                $carte2 = $concurrence->carte[1];
                $carte3 = $concurrence->carte[2];
                $carte4 = $concurrence->carte[3];
                $carte1 = $this->getDoctrine()->getRepository("App:Objet")->find($carte1);
                $carte2 = $this->getDoctrine()->getRepository("App:Objet")->find($carte2);
                $carte3 = $this->getDoctrine()->getRepository("App:Objet")->find($carte3);
                $carte4 = $this->getDoctrine()->getRepository("App:Objet")->find($carte4);
                return $this->render("game/concurrence_suite2.html.twig", ['carte1' => $carte1,'carte2' => $carte2, 'carte3' => $carte3, 'carte4'=>$carte4, 'joueur'=>$joueur, 'idpartie'=>$idpartie]);
            }
            else{
                $carte1 = $this->getDoctrine()->getRepository("App:Objet")->find(22);
                $carte2 = $this->getDoctrine()->getRepository("App:Objet")->find(22);
                $carte3 = $this->getDoctrine()->getRepository("App:Objet")->find(22);
                $carte4 = $this->getDoctrine()->getRepository("App:Objet")->find(22);
                return $this->render("game/concurrence_suite2.html.twig", ['carte1' => $carte1,'carte2' => $carte2, 'carte3' => $carte3, 'carte4'=>$carte4, 'joueur'=>$joueur, 'idpartie'=>$idpartie]);
            }
        }
    }

    /**
     * @Route("/action/concurrence_traitement/{carte1}/{carte2}/{joueur}/{idpartie}", name="concurrence_traitement")
     */
    public function concurrence_traitement($carte1, $carte2, $joueur, $idpartie){
        $carte1 = $this->getDoctrine()->getRepository("App:Objet")->find($carte1);
        $carte2 = $this->getDoctrine()->getRepository("App:Objet")->find($carte2);
        $entityManager = $this->getDoctrine()->getManager();
        $partie = $entityManager->getRepository("App:Partie")->find($idpartie);
        if($joueur == 'j1'){
            print_r($carte1->getId());
            //je modifie son action dispariotn
            $action = $partie->getActionJ1();
            $concurrenceA = $action->concurrenceA;
            $concurrenceA->carte = array($carte1->getId(), $carte2->getId());
            $action->concurrenceA = $concurrenceA;
            $partie->setActionJ1(json_encode($action));

            $action = $partie->getActionJ2();
            $concurrence = $action->concurrence;
            $concurrence->etat = 1;
            $carte = $concurrence->carte;
            $tconcurrence = array();
            foreach ($carte as  $value) {
                if($value != $carte1->getId() && $value != $carte2->getId()){
                    $tconcurrence[] = $value;
                }
            }

            $concurrence->carte = $tconcurrence;
            $action->concurrence = $concurrence;
            $partie->setActionJ2(json_encode($action));

            $pioche = $partie->getPioche();
            $carte_piochee = array_pop($pioche);
            $main = $partie->getMainJ1();
            if($carte_piochee != null){
                $main[] = $carte_piochee;
            }
            $partie->setMainJ1(json_encode($main));
            $partie->setPioche(json_encode($pioche));

            $em = $this->getDoctrine()->getManager();
            $em->persist($partie);
            $em->flush();
            return $this->redirectToRoute('afficher_partie', ['id' => $idpartie, 'partie'=>$partie]);
        }
        elseif($joueur == 'j2'){
            //je modifie son action dispariotn
            $action = $partie->getActionJ2();
            $concurrenceA = $action->concurrenceA;
            $concurrenceA->carte = array($carte1->getId(), $carte2->getId());
            $action->concurrenceA = $concurrenceA;
            $partie->setActionJ2(json_encode($action));

            $action = $partie->getActionJ1();
            $concurrence = $action->concurrence;
            $concurrence->etat = 1;
            $carte = $concurrence->carte;
            $tconcurrence = array();
            foreach ($carte as  $value) {
                if($value != $carte1->getId() && $value != $carte2->getId()){
                    $tconcurrence[] = $value;
                }
            }

            $concurrence->carte = $tconcurrence;
            $action->concurrence = $concurrence;
            $partie->setActionJ1(json_encode($action));

            $pioche = $partie->getPioche();
            $carte_piochee = array_pop($pioche);
            $main = $partie->getMainJ2();
            if($carte_piochee != null){
                $main[] = $carte_piochee;
            }
            $partie->setMainJ2(json_encode($main));
            $partie->setPioche(json_encode($pioche));

            $em = $this->getDoctrine()->getManager();
            $em->persist($partie);
            $em->flush();
            return $this->redirectToRoute('afficher_partie', ['id' => $idpartie, 'partie'=>$partie]);
        }
    }
}