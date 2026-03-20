<?php
namespace App\Controller\Admin;

use App\Entity\Playlist;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur admin des playlists
 */
class AdminPlaylistsController extends AbstractController {

    private $playlistRepository;
    private $formationRepository;
    private $categorieRepository;
    private const PAGE_ADMIN_PLAYLISTS = "admin/playlists.html.twig";
    private const PAGE_ADMIN_PLAYLIST_FORM = "admin/playlist_form.html.twig";

    public function __construct(
        PlaylistRepository $playlistRepository,
        FormationRepository $formationRepository,
        CategorieRepository $categorieRepository
    ) {
        $this->playlistRepository = $playlistRepository;
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }

    #[Route('/admin/playlists', name: 'admin.playlists')]
    public function index(): Response {
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_ADMIN_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    #[Route('/admin/playlists/tri/{champ}/{ordre}', name: 'admin.playlists.sort')]
    public function sort($champ, $ordre): Response {
        switch($champ) {
            case "name":
                $playlists = $this->playlistRepository->findAllOrderByName($ordre);
                break;
            case "nbFormations":
                $playlists = $this->playlistRepository->findAllOrderByNbFormations($ordre);
                break;
            default:
                $playlists = $this->playlistRepository->findAllOrderByName('ASC');
                break;
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_ADMIN_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    #[Route('/admin/playlists/recherche/{champ}/{table}', name: 'admin.playlists.findallcontain')]
    public function findAllContain($champ, Request $request, $table = ""): Response {
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_ADMIN_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    #[Route('/admin/playlists/ajouter', name: 'admin.playlists.ajouter')]
    public function ajouter(Request $request): Response {
        // Si le formulaire est bien soumis
        if($request->get("name")){
            $playlist = new Playlist();
            $playlist->setName($request->get('name'));
            $playlist->setDescription($request->get('description'));
            $this->playlistRepository->add($playlist);
            return $this->redirectToRoute('admin.playlists');
        }

        // Sinon on affiche le formulaire vide
        return $this->render(self::PAGE_ADMIN_PLAYLIST_FORM, [
            'playlist' => new Playlist(),
            'formations' => [],
            'estModification' => false
        ]);
    }

    #[Route('/admin/playlists/modifier/{id}', name: 'admin.playlists.modifier')]
    public function modifier(int $id, Request $request): Response {
        $playlist = $this->playlistRepository->find($id);

        // Si le formulaire est bien soumis
        if($request->get("name")){
            $playlist->setName($request->get('name'));
            $playlist->setDescription($request->get('description'));
            $this->playlistRepository->add($playlist);
            return $this->redirectToRoute('admin.playlists');
        }

        // Sinon on affiche le formulaire prérempli avec la liste des formations
        $formations = $this->formationRepository->findAllForOnePlaylist($id);
        return $this->render(self::PAGE_ADMIN_PLAYLIST_FORM, [
            'playlist' => $playlist,
            'formations' => $formations,
            'estModification' => true
        ]);
    }
    
    #[Route('/admin/playlists/supprimer/{id}', name: 'admin.playlists.supprimer')]
    public function supprimer(int $id): Response {
        $playlist = $this->playlistRepository->find($id);
        // On vérifie qu'il n'y a pas de formations rattachées
        if($playlist->getFormations()->count() > 0){
            return $this->redirectToRoute('admin.playlists');
        }
        $this->playlistRepository->remove($playlist);
        return $this->redirectToRoute('admin.playlists');
    }
}