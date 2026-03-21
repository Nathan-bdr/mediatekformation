<?php
namespace App\Tests\Validations;

use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FormationValidationTest extends KernelTestCase {

    // Méthode qui vérifie le nombre d'erreurs
    public function assertErrors(Formation $formation, int $nbErreurs) {
        self::bootKernel();
        $validator = self::getContainer()->get('validator');
        $errors = $validator->validate($formation);
        $this->assertCount($nbErreurs, $errors);
    }

    // Test avec une date correcte (aujourd'hui) : aucune erreur attendue
    public function testDateValide() {
        $formation = new Formation();
        $formation->setPublishedAt(new \DateTime("now"));
        $this->assertErrors($formation, 0);
    }

    // Test avec une date future : une erreur attendue
    public function testDateNonValide() {
        $formation = new Formation();
        $formation->setPublishedAt(new \DateTime("2030-01-01"));
        $this->assertErrors($formation, 1);
    }
}