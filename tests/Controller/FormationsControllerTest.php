<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FormationsControllerTest extends WebTestCase {

    // Test que la page formations est accessible
    public function testAccesPageFormations() {
        $client = static::createClient();
        $client->request('GET', '/formations');
        $this->assertResponseStatusCodeSame(200);
    }

    // Test le tri par titre ASC : vérifie le titre de la première ligne
    public function testTriTitreAsc() {
        $client = static::createClient();
        $client->request('GET', '/formations/tri/title/ASC');
        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('h5', 'Android Studio (complément n°1) : Navigation Drawer et Fragment');
    }

    // Test le tri par titre DESC : vérifie le titre de la première ligne
    public function testTriTitreDesc() {
        $client = static::createClient();
        $client->request('GET', '/formations/tri/title/DESC');
        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('h5', 'UML : Diagramme de paquetages');
    }

    // Test le filtre par titre : vérifie le nombre de résultats et la première ligne
    public function testFiltreParTitre() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/recherche/title', ['recherche' => 'Eclipse']);
        $this->assertResponseStatusCodeSame(200);
        $nbFormations = $crawler->filter('h5')->count();
        $this->assertGreaterThan(0, $nbFormations);
        $this->assertSelectorTextContains('h5', 'Eclipse');
    }

    // Test le clic sur une formation : vérifie l'accès à la page détail
    public function testClicFormation() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $link = $crawler->filter('td a')->first()->link();
        $client->click($link);
        $this->assertResponseStatusCodeSame(200);
    }
}