<?php

namespace App\Controller;

use App\Entity\Vehicule;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Attributes as OA;


#[Route('/api/vehicule')]
#[OA\Tag(name: "Vehicule")]
class VehiculeController extends AbstractController
{
    private $serializer;
    private $entity;

    public function __construct(SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $this->serializer = $serializer;
        $this->entity = $em;
    }

    #[Route('/', name: 'api_vehicules', methods: ['GET'])]
    #[OA\Get(
        summary: "Obtenir tous les véhicules",
        responses: [
            new OA\Response(
                response: 200,
                description: "Réponse réussie",
                content: new OA\JsonContent(type: "array", items: new OA\Items(ref: "#/components/schemas/Vehicule"))
            )
        ]
    )]
    public function index(): JsonResponse
    {
        $vehicules = $this->entity->getRepository(Vehicule::class)->findAll();
        return JsonResponse::fromJsonString($this->serializer->serialize($vehicules, 'json'));
    }

    #[Route('/{id}', name: 'api_vehicule_show', methods: ['GET'])]
    #[OA\Get(
        summary: "Obtenir un véhicule par ID",
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer"),
                description: "L'ID du véhicule"
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Réponse réussie",
                content: new OA\JsonContent(ref: "#/components/schemas/Vehicule")
            ),
            new OA\Response(
                response: 404,
                description: "Véhicule non trouvé"
            )
        ]
    )]
    public function show(Vehicule $vehicule): JsonResponse
    {
        return JsonResponse::fromJsonString($this->serializer->serialize($vehicule, 'json'));
    }

    #[Route('/new', name: 'api_vehicule_new', methods: ['POST'])]
    #[OA\Post(
        summary: "Créer un nouveau véhicule",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/Vehicule")
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Véhicule créé avec succès",
                content: new OA\JsonContent(ref: "#/components/schemas/Vehicule")
            ),
            new OA\Response(
                response: 400,
                description: "Données invalides",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string')
                    ]
                )
            )
        ]
    )]
    public function new(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->json(['error' => 'Données invalides'], Response::HTTP_BAD_REQUEST);
        }

        $requiredFields = ['plaqueImmatriculation', 'typeVehicule', 'dateMiseEnCirculation'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                return $this->json(['error' => "Le champ $field est obligatoire"], Response::HTTP_BAD_REQUEST);
            }
        }

        try {
            $vehicule = new Vehicule();
            $vehicule->setPlaqueImmatriculation($data['plaqueImmatriculation']);
            $vehicule->setTypeVehicule($data['typeVehicule']);
            $vehicule->setPhotos($data['photos'] ?? []);
            $vehicule->setDateMiseEnCirculation(new DateTime($data['dateMiseEnCirculation']));

            $em->persist($vehicule);
            $em->flush();

            return $this->json($vehicule, Response::HTTP_CREATED);
        } catch (\Exception $ex) {
            return $this->json(['error' => $ex->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{id}', name: 'api_vehicule_update', methods: ['PUT'])]
    #[OA\Put(
        summary: "Mettre à jour un véhicule existant",
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer"),
                description: "L'ID du véhicule"
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/Vehicule")
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Véhicule mis à jour avec succès",
                content: new OA\JsonContent(ref: "#/components/schemas/Vehicule")
            ),
            new OA\Response(
                response: 400,
                description: "Données invalides",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string')
                    ]
                )
            )
        ]
    )]
    public function update(Request $request, Vehicule $vehicule, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->json(['error' => 'Données invalides'], Response::HTTP_BAD_REQUEST);
        }
        try {
            $vehicule->setPlaqueImmatriculation($data['plaqueImmatriculation']);
            $vehicule->setTypeVehicule($data['typeVehicule']);
            $vehicule->setPhotos($data['photos'] ?? []);
            $vehicule->setDateMiseEnCirculation(new DateTime($data['dateMiseEnCirculation']));
            $em->flush();

            return $this->json($vehicule, Response::HTTP_OK);
        } catch (\Exception $ex) {
            return $this->json(['error' => $ex->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{id}', name: 'api_vehicule_delete', methods: ['DELETE'])]
    #[OA\Delete(
        summary: "Supprimer un véhicule",
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer"),
                description: "L'ID du véhicule"
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: "Véhicule supprimé avec succès"
            ),
            new OA\Response(
                response: 400,
                description: "ID invalide",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string')
                    ]
                )
            )
        ]
    )]
    public function delete(Vehicule $vehicule, EntityManagerInterface $em): JsonResponse
    {
        try {
            $em->remove($vehicule);
            $em->flush();
            return $this->json(['success' => 'Véhicule effacé'], Response::HTTP_NO_CONTENT);
        } catch (\Exception $ex) {
            return $this->json(['error' => $ex->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
