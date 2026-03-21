<?php
namespace App\Tests\Repository;

use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FormationRepositoryTest extends KernelTestCase {

    public function recupRepository(): FormationRepository {
        self::bootKernel();
        return self::getContainer()->get(FormationRepository::class);
    }

    // Test findAllOrderBy : vérifie que le tri sur le titre fonctionne
    public function testFindAllOrderBy() {
        $repository = $this->recupRepository();
        $formations = $repository->findAllOrderBy('title', 'ASC');
        $this->assertEquals("Android Studio (complément n°1) : Navigation Drawer et Fragment", $formations[0]->getTitle());
    }

    // Test findByContainValue : vérifie le nombre de résultats
    public function testFindByContainValue() {
        $repository = $this->recupRepository();
        $formations = $repository->findByContainValue('title', 'a', '');
        $this->assertGreaterThan(0, count($formations));
    }

    // Test findAllLasted : vérifie qu'on obtient bien le bon nombre de formations
    public function testFindAllLasted() {
        $repository = $this->recupRepository();
        $formations = $repository->findAllLasted(3);
        $this->assertCount(3, $formations);
    }

    // Test findAllForOnePlaylist : vérifie qu'on obtient les formations d'une playlist
    public function testFindAllForOnePlaylist() {
        $repository = $this->recupRepository();
        $formations = $repository->findAllForOnePlaylist(2);
        $this->assertGreaterThan(0, count($formations));
    }
}