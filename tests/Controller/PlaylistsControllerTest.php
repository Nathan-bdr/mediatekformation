<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PlaylistsControllerTest extends WebTestCase {

    // Test que la page playlists est accessible
    public function testAccesPagePlaylists() {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $this->assertResponseStatusCodeSame(200);
    }

    // Test le tri par nom ASC : vérifie le nom de la première ligne
    public function testTriNomAsc() {
        $client = static::createClient();
        $client->request('GET', '/playlists/tri/name/ASC');
        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('h5', '(C#)');
    }

    // Test le tri par nom DESC : vérifie le nom de la première ligne
    public function testTriNomDesc() {
        $client = static::createClient();
        $client->request('GET', '/playlists/tri/name/DESC');
        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('h5', '2019');
    }

    // Test le filtre par nom : vérifie le nombre de résultats et la première ligne
    public function testFiltreParNom() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists/recherche/name', ['recherche' => 'Android']);
        $this->assertResponseStatusCodeSame(200);
        $nbPlaylists = $crawler->filter('h5')->count();
        $this->assertGreaterThan(0, $nbPlaylists);
        $this->assertSelectorTextContains('h5', 'Android');
    }

    // Test le clic sur une playlist : vérifie l'accès à la page détail
    public function testClicPlaylist() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists');
        $client->clickLink('Voir détail');
        $this->assertResponseStatusCodeSame(200);
    }
}