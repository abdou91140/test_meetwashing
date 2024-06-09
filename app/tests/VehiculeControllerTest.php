<?php

namespace App\Tests;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VehiculeControllerTest extends WebTestCase
{
    public function testGetAllVehicles()
    {
        $client = static::createClient();
        $client->request('Get', '/api/vehicule');
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }

    public function testCreateVehicle()
    {
        $client = static::createClient();
        $client->request('POST', '/api/vehicule/new', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'plaqueImmatriculation' => 'ABC-123',
            'typeVehicule' => 'Car',
            'photos' => 'photo1.jpg',
            'dateMiseEnCirculation' => '2024-06-06',
        ]));
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }


    public function testGetVehicle()
    {
        $client = static::createClient();
        $client->request('Get', '/api/vehicule/1');
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }

    public function testUpdateVehicle()
    {
        $client = static::createClient();
        $client->request('PUT', '/api/vehicule/1', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'plaqueImmatriculation' => '25-er-123',
            'typeVehicule' => 'Moto',
            'photos' => 'photo3.jpg',
            'dateMiseEnCirculation' => '2023-07-07',
        ]));
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }

    public function testDeleteVehicle()
    {
        $client = static::createClient();
        $client->request('DELETE', '/api/vehicule/1');
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }
}
