<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Impression;
use App\Form\FilmType;
use App\Form\ImpressionType;
use App\Repository\FilmRepository;
use App\Repository\ImpressionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FilmController extends AbstractController
{
    #[Route('/', name: 'film')]
    public function index(FilmRepository $film): Response
    {

        return $this->render('film/index.html.twig', [
            'controller_name' => 'Nos Films',
            'lesFilms' => $film->findAll()
        ]);
    }

    /**
     * @Route("/unFilm/{id}", name="unFilm")
     */
    public function show(Film $film, Request $laRequete, EntityManagerInterface $manager, ImpressionRepository $impressions)
    {
        $impressions = $impressions->findAll();
        $impression = new Impression();

        $formulaire = $this->createForm(ImpressionType::class, $impression);

        $formulaire->handleRequest($laRequete);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            $impression->setFilm($film);



            $manager->persist($impression);
            $manager->flush();

            return $this->redirectToRoute('unFilm', ["id" => $film->getId()]);
        }


        return $this->renderForm('film/show.html.twig', [
            'controller_name' => $film->getNom(),
            'unFilm' => $film,
            'impression' => $impressions,
            'formulaire' => $formulaire
        ]);
    }

    /**
     * @Route("/creerFilm", name="creerFilm")
     */
    public function new(Request $laRequete, EntityManagerInterface $manager)
    {

        $film = new Film();

        $formulaire = $this->createForm(FilmType::class, $film);

        $formulaire->handleRequest($laRequete);

        if ($formulaire->isSubmitted()) {
            $film = $formulaire->getData();


            $manager->persist($film);
            $manager->flush();

            return $this->redirectToRoute('film');
        }

        return $this->renderForm('film/new.html.twig', ['leFormulaire' => $formulaire]);
    }

    /**
     * @Route("/unFilm/supprimerFilm/{id}", name="supprimerFilm")
     */
    public function delete(Film $film = null, EntityManagerInterface $manager)
    {
        if ($film) {
            $manager->remove($film);
            $manager->flush();
        }

        return $this->redirectToRoute("film");
    }

    /**
     * @route("/unFilm/modifierFilm/{id}", name="modifierFilm")
     */
    public function change(Film $film, request $larequete, entitymanagerinterface $manager)
    {
        $formulaire = $this->createform(FilmType::class, $film);

        $formulaire->handlerequest($larequete);

        if ($formulaire->issubmitted()) {
            $formulaire->getdata();

            $manager->persist($film);
            $manager->flush();

            return $this->redirecttoroute("unFilm", ["id" => $film->getid()]);
        }

        return $this->renderform("film/edit.html.twig", ["formulaire" => $formulaire]);
    }
}
