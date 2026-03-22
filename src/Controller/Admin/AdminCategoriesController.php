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
        // Si le formulaire d'ajout est bien soumis
        if($request->get("name")){
            $name = $request->get('name');
            // On vérifie que la catégorie n'existe pas déjà
            $existante = $this->categorieRepository->findOneBy(['name' => $name]);
            if(!$existante){
                $categorie = new Categorie();
                $categorie->setName($name);
                $this->categorieRepository->add($categorie);
            }
            return $this->redirectToRoute('admin.categories');
        }

        $categories = $this->categorieRepository->findBy([], ['name' => 'ASC']);
        return $this->render(self::PAGE_ADMIN_CATEGORIES, [
            'categories' => $categories
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
        // On vérifie qu'aucune formation n'est rattachée
        if($categorie->getFormations()->count() > 0){
            return $this->redirectToRoute('admin.categories');
        }
        $this->categorieRepository->remove($categorie);
        return $this->redirectToRoute('admin.categories');
    }
}