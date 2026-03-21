<?php
namespace App\Tests;

use App\Entity\Formation;
use PHPUnit\Framework\TestCase;

class FormationTest extends TestCase {

    public function testGetPublishedAtString() {
        $formation = new Formation();
        $formation->setPublishedAt(new \DateTime("2024-04-22"));
        $this->assertEquals("22/04/2024", $formation->getPublishedAtString());
    }
}