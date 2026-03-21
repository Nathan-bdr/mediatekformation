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
 */
class AdminCategoriesController extends AbstractController {

    private $categorieRepository;
    private const PAGE_ADMIN_CATEGORIES = "admin/categories.html.twig";

    public function __construct(CategorieRepository $categorieRepository) {
        $this->categorieRepository = $categorieRepository;
    }

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