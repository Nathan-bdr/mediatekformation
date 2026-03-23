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
 * @author Nathan Boudier
 */
class AdminPlaylistsController extends AbstractController {

    /**
     * @var PlaylistRepository
     */
    private $playlistRepository;
    
    /**
     * @var FormationRepository
     */
    private $formationRepository;
    
    /**
     * @var CategorieRepository
     */
    private $categorieRepository;
    
    /**
     * Chemin vers la page admin 'playlists'
     */
    private const PAGE_ADMIN_PLAYLISTS = "admin/playlists.html.twig";
    
    /**
     * Chemin vers le formulaire admin 'playlist'
     */
    private const PAGE_ADMIN_PLAYLIST_FORM = "admin/playlist_form.html.twig";

    /**
     * @param PlaylistRepository $playlistRepository
     * @param FormationRepository $formationRepository
     * @param CategorieRepository $categorieRepository
     */
    public function __construct(
        PlaylistRepository $playlistRepository,
        FormationRepository $formationRepository,
        CategorieRepository $categorieRepository
    ) {
        $this->playlistRepository = $playlistRepository;
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }

    /**
     * Affiche la liste de toutes les playlists
     * @return Response
     */
    #[Route('/admin/playlists', name: 'admin.playlists')]
    public function index(Request $request): Response {
        $message = $request->get('message', '');
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_ADMIN_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories,
            'message' => $message
        ]);
    }

    /**
     * Affiche la liste des playlists triées sur un champ
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
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

    /**
     * Affiche la liste des playlists dont un champ contient une valeur
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
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

    /**
     * Affiche le formulaire d'ajout et gère l'ajout d'une playlist
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/playlists/ajouter', name: 'admin.playlists.ajouter')]
    public function ajouter(Request $request): Response {
        if($request->get("name")){
            $playlist = new Playlist();
            $playlist->setName($request->get('name'));
            $playlist->setDescription($request->get('description'));
            $this->playlistRepository->add($playlist);
            return $this->redirectToRoute('admin.playlists', ['message' => 'Playlist ajoutée avec succès.']);
        }
        return $this->render(self::PAGE_ADMIN_PLAYLIST_FORM, [
            'playlist' => new Playlist(),
            'formations' => [],
            'estModification' => false
        ]);
    }

    /**
     * Affiche le formulaire de modification et gère la modification d'une playlist
     * @param int $id
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/playlists/modifier/{id}', name: 'admin.playlists.modifier')]
    public function modifier(int $id, Request $request): Response {
        $playlist = $this->playlistRepository->find($id);

        // Si le formulaire est bien soumis
        if($request->get("name")){
            $playlist->setName($request->get('name'));
            $playlist->setDescription($request->get('description'));
            $this->playlistRepository->add($playlist);
            return $this->redirectToRoute('admin.playlists', ['message' => 'Playlist modifiée avec succès.']);
        }

        // Sinon on affiche le formulaire prérempli avec la liste des formations
        $formations = $this->formationRepository->findAllForOnePlaylist($id);
        return $this->render(self::PAGE_ADMIN_PLAYLIST_FORM, [
            'playlist' => $playlist,
            'formations' => $formations,
            'estModification' => true
        ]);
    }
    
    /**
     * Supprime une playlist si aucune formation n'y est rattachée
     * @param int $id
     * @return Response
     */
    #[Route('/admin/playlists/supprimer/{id}', name: 'admin.playlists.supprimer')]
    public function supprimer(int $id): Response {
        $playlist = $this->playlistRepository->find($id);
        // On vérifie qu'il n'y a pas de formations rattachées
        if($playlist->getFormations()->count() > 0){
            return $this->redirectToRoute('admin.playlists', ['message' => 'Impossible de supprimer : des formations sont rattachées à cette playlist.']);
        }
        $this->playlistRepository->remove($playlist);
        return $this->redirectToRoute('admin.playlists', ['message' => 'Playlist supprimée avec succès.']);
    }
}