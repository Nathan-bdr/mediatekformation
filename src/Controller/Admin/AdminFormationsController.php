<?php
namespace App\Controller\Admin;

use App\Entity\Formation;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur admin des formations
 */
class AdminFormationsController extends AbstractController {

    private $formationRepository;
    private $playlistRepository;
    private $categorieRepository;
    private const PAGE_ADMIN_FORMATIONS = "admin/formations.html.twig";
    private const PAGE_ADMIN_FORMATION_FORM = "admin/formation_form.html.twig";

    public function __construct(
        FormationRepository $formationRepository,
        PlaylistRepository $playlistRepository,
        CategorieRepository $categorieRepository
    ) {
        $this->formationRepository = $formationRepository;
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
    }

    #[Route('/admin/formations', name: 'admin.formations')]
    public function index(): Response {
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_ADMIN_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    #[Route('/admin/formations/tri/{champ}/{ordre}/{table}', name: 'admin.formations.sort')]
    public function sort($champ, $ordre, $table = ""): Response {
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_ADMIN_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    #[Route('/admin/formations/recherche/{champ}/{table}', name: 'admin.formations.findallcontain')]
    public function findAllContain($champ, Request $request, $table = ""): Response {
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_ADMIN_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    #[Route('/admin/formations/ajouter', name: 'admin.formations.ajouter')]
    public function ajouter(Request $request): Response {
        // Si le formulaire est bien soumis
        if($request->get("title")){
            $formation = new Formation();
            $formation->setTitle($request->get('title'));
            $formation->setDescription($request->get('description'));
            $formation->setVideoId($request->get('videoId'));

            // On définit la date de publication
            $date = new \DateTime($request->get('publishedAt'));
            $formation->setPublishedAt($date);

            // On définit la playlist
            $playlist = $this->playlistRepository->find($request->get('playlist'));
            $formation->setPlaylist($playlist);

            // On définit la catégorie
            $categorieIds = $request->get('categories', []);
            foreach($categorieIds as $categorieId){
                $categorie = $this->categorieRepository->find($categorieId);
                $formation->addCategory($categorie);
            }

            $this->formationRepository->add($formation);
            return $this->redirectToRoute('admin.formations');
        }

        // Sinon on affiche le formulaire vide
        return $this->render(self::PAGE_ADMIN_FORMATION_FORM, [
            'formation' => new Formation(),
            'playlists' => $this->playlistRepository->findAll(),
            'categories' => $this->categorieRepository->findAll(),
            'estModification' => false
        ]);
    }

    #[Route('/admin/formations/modifier/{id}', name: 'admin.formations.modifier')]
    public function modifier(int $id, Request $request): Response {
        $formation = $this->formationRepository->find($id);

        // Si le formulaire est bien soumis
        if($request->get("title")){
            $formation->setTitle($request->get('title'));
            $formation->setDescription($request->get('description'));
            $formation->setVideoId($request->get('videoId'));

            // On met à jours la date de publication
            $date = new \DateTime($request->get('publishedAt'));
            $formation->setPublishedAt($date);

            // On met à jours la playlist
            $playlist = $this->playlistRepository->find($request->get('playlist'));
            $formation->setPlaylist($playlist);

            // On met à jours les catégories
            $formation->getCategories()->clear();
            $categorieIds = $request->get('categories', []);
            foreach($categorieIds as $categorieId){
                $categorie = $this->categorieRepository->find($categorieId);
                $formation->addCategory($categorie);
            }

            $this->formationRepository->add($formation);
            return $this->redirectToRoute('admin.formations');
        }

        // Sinon on affiche le formulaire prérempli
        return $this->render(self::PAGE_ADMIN_FORMATION_FORM, [
            'formation' => $formation,
            'playlists' => $this->playlistRepository->findAll(),
            'categories' => $this->categorieRepository->findAll(),
            'estModification' => true
        ]);
    }
    
    #[Route('/admin/formations/supprimer/{id}', name: 'admin.formations.supprimer')]
    public function supprimer(int $id): Response {
        $formation = $this->formationRepository->find($id);
        $this->formationRepository->remove($formation);
        return $this->redirectToRoute('admin.formations');
    }
}