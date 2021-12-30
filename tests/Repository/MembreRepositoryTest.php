<?php

namespace App\Tests\Repository;

use App\Repository\MembreRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MembreRepositoryTest extends KernelTestCase
{
    public function testCount(){
        $kernel = self::bootKernel();
        $membres = $kernel->getContainer()->get(MembreRepository::class)->count([]);
        $this->assertEquals(10, $membres);
    }
}