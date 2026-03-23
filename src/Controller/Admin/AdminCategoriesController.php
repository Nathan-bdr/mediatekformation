<?php
namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur admin des catégories
 * @author Nathan Boudier
 */
class AdminCategoriesController extends AbstractController {

    /**
     * @var CategorieRepository
     */
    private $categorieRepository;
    
    /**
     * Chemin vers la page admin 'catégories'
     */
    private const PAGE_ADMIN_CATEGORIES = "admin/categories.html.twig";

    /**
     * @param CategorieRepository $categorieRepository
     */
    public function __construct(CategorieRepository $categorieRepository) {
        $this->categorieRepository = $categorieRepository;
    }

    /**
     * Affiche la liste des catégories et gère l'ajout d'une nouvelle catégorie
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/categories', name: 'admin.categories')]
    public function index(Request $request): Response {
        $message = $request->get('message', '');
        if($request->get("name")){
            $name = $request->get('name');
            $existante = $this->categorieRepository->findOneBy(['name' => $name]);
            if(!$existante){
                $categorie = new Categorie();
                $categorie->setName($name);
                $this->categorieRepository->add($categorie);
                return $this->redirectToRoute('admin.categories', ['message' => 'Catégorie ajoutée avec succès.']);
            }
            return $this->redirectToRoute('admin.categories', ['message' => 'Cette catégorie existe déjà.']);
        }
        $categories = $this->categorieRepository->findBy([], ['name' => 'ASC']);
        return $this->render(self::PAGE_ADMIN_CATEGORIES, [
            'categories' => $categories,
            'message' => $message
        ]);
    }

    /**
     * Supprime une catégorie si aucune formation n'y est rattachée
     * @param int $id
     * @return Response
     */
    #[Route('/admin/categories/supprimer/{id}', name: 'admin.categories.supprimer')]
    public function supprimer(int $id): Response {
        $categorie = $this->categorieRepository->find($id);
        if($categorie->getFormations()->count() > 0){
            return $this->redirectToRoute('admin.categories', ['message' => 'Impossible de supprimer : cette catégorie est rattachée à des formations.']);
        }
        $this->categorieRepository->remove($categorie);
        return $this->redirectToRoute('admin.categories', ['message' => 'Catégorie supprimée avec succès.']);
    }
}