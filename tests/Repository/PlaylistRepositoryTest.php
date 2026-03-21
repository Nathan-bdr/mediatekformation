<?php
namespace App\Tests\Repository;

use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PlaylistRepositoryTest extends KernelTestCase {

    public function recupRepository(): PlaylistRepository {
        self::bootKernel();
        return self::getContainer()->get(PlaylistRepository::class);
    }

    // Test findAllOrderByName : vérifie que le premier résultat est bien trié
    public function testFindAllOrderByName() {
        $repository = $this->recupRepository();
        $playlists = $repository->findAllOrderByName('ASC');
        $this->assertEquals("Bases de la programmation (C#)", $playlists[0]->getName());
    }

    // Test findAllOrderByNbFormations : vérifie que le tri fonctionne
    public function testFindAllOrderByNbFormations() {
        $repository = $this->recupRepository();
        $playlists = $repository->findAllOrderByNbFormations('ASC');
        $this->assertEquals(0, count($playlists[0]->getFormations()));
    }

    // Test findByContainValue : vérifie le nombre de résultats
    public function testFindByContainValue() {
        $repository = $this->recupRepository();
        $playlists = $repository->findByContainValue('name', 'a', '');
        $this->assertGreaterThan(0, count($playlists));
    }
}