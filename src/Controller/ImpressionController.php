<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Impression;
use App\Form\ImpressionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImpressionController extends AbstractController
{
    /**
     * @route("/new_impr/{id}", name="new_impr")
     */
    public function new(EntityManagerInterface $manager, Request $laRequete, Film $film)
    {
        $impression = new Impression();


        $formulaire = $this->createform(ImpressionType::class, $impression);

        $formulaire->handleRequest($laRequete);

        if ($formulaire->isSubmitted()) {
            $film = $impression->setFilm($film);


            $manager->persist($impression);
            $manager->flush();

            return $this->redirectToRoute('unFilm', ["date" => $impression->setDate(new \DateTime()), "id" => $impression->getFilm()->getid()]);
        }
    }

    /**
     * @route("/unFilm/supprimerImpression/{id}", name="supprimerImpression")
     */
    public function delete(impression $impression = null, entitymanagerinterface $manager)
    {


        if ($impression) {
            $manager->remove($impression);
            $manager->flush();
        }

        return $this->redirecttoroute("unFilm", ["id" => $impression->getFilm()->getid()]);
    }

    /**
     * @route("/unFilm/modifierImpression/{id}", name="modifierImpression")
     */
    public function change(Impression $impression, request $larequete, entitymanagerinterface $manager)
    {
        $formulaire = $this->createform(ImpressionType::class, $impression);

        $formulaire->handlerequest($larequete);

        if ($formulaire->issubmitted()) {
            $formulaire->getdata();

            $manager->persist($impression);
            $manager->flush();

            return $this->redirecttoroute("unFilm", ["id" => $impression->getFilm()->getid()]);
        }

        return $this->renderform("impression/edit.html.twig", ["formulaire" => $formulaire]);
    }
}
