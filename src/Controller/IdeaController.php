<?php

namespace App\Controller;

use App\Entity\Idea;
use App\Entity\category;
use App\Form\IdeaType;
use App\Repository\IdeaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class IdeaController extends AbstractController
{
    /**
     * @Route("/ideas", name="idea_list")
     */
    public function list(IdeaRepository $ideaRepository)
    {
        // todo : aller chercher toutes les dernières idées dans la bdd
        // récupère les 50 idées publiées les plus récentes
        // limitée à 50 idées pour ne pas faire planter le site
        $ideas = $ideaRepository ->findWithCategories();
        // test de public/ideas
        //dd($ideas);

        // le tableau [] permet de rendre le tableau accessible à twig
        return $this->render('idea/list.html.twig', [
            "ideas" => $ideas
        ]);
    }

    /**
     * @Route("/ideas/details/{id}", name="idea_details", requirements={"id": "\d+"})
     */
    public function details($id, IdeaRepository $ideaRepository)
    {
        // todo : aller chercher cette idée dans la bdd (= faire un SELECT) donc je prépare le tableau : []
        //$idea = $ideaRepository->find($id);

        //récupérer une idée en fonction de la clé primaire :
        $idea = $ideaRepository->find($id);

        if (!$idea) {
            throw $this->createNotFoundException("cette idée n'existe pas !");
        }

        return $this->render('idea/details.html.twig', [
            "idea" => $idea
        ]);
    }


    /**
     * @Route("/ideas/add", name="idea_add")
     */
    public function add(Request $request, EntityManagerInterface $entityManager)
    {
        // interdit l'accès à tout le monde, sauf aux utilisateurs connectés ayan le rôle ROLE_USER
        $this->denyAccessUnlessGranted("ROLE_USER");

        $idea = new Idea();
        //$idea->setAuthor('toto');
        $idea->setAuthor($this->getUser()->getUsername());
        $ideaForm = $this->createForm(IdeaType::class, $idea);
        $ideaForm ->handleRequest($request);

        if ($ideaForm->isSubmitted() && $ideaForm->isValid())  {
            $idea->setIsPublished(true);
            $idea->setDateCreate(new \DateTime());

            $entityManager->persist($idea);
            $entityManager->flush();

         $this->addFlash('success', 'Le produit a bien été ajouté');

        return $this->redirectToRoute('idea_add');
        }

        //dump($idea);
        //die();




        return $this->render('idea/add.html.twig', [
            "ideaForm" => $ideaForm->createView()
        ]);
    }

}
