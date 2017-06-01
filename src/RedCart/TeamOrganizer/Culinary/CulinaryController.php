<?php

declare(strict_types=1);

namespace RedCart\TeamOrganizer\Culinary;

use RedCart\TeamOrganizer\Foundation\AbstractController;

class CulinaryController extends AbstractController
{
    /**
     * @route GET /
     */
    public function index()
    {
        $repository = new CulinaryDaysRepository();
        $declarations = $repository->getDeclarations();
        $availableEmployees = $this->get('config')['employees'];

        $projections = [];
        foreach ($declarations as $declaration) {
            // usuń pracowników, którzy już się zadeklarowali
            foreach ($declaration->getPersons() as $person) {
                if (in_array($person, $availableEmployees)) {
                    $key = array_search($person, $availableEmployees);
                    unset($availableEmployees[$key]);
                }
            }
            // grupuj deklaracje po restauracji
            $projections[$declaration->getRestaurant()][] = $declaration;
        }

        $projections = array_map(function ($declarations, $key) {
            $projection = new Projection($key);
            $projection->addDeclarations($declarations);
            return $projection;
        }, $projections, array_keys($projections));

        $alreadyDeclared = $repository->hasDeclarationByUUID($_SESSION['culinary-declaration'] ?? '');

        $this->get('twig')->display('culinary/declarations.twig', [
            'title' => 'Kulinarne piątki',
            'projections' => $projections,
            'availableEmployees' => $availableEmployees,
            'alreadyDeclared' => $alreadyDeclared,
        ]);
    }

    /**
     * @route POST /
     */
    public function insertDeclaration()
    {
        $declaration = new Declaration($_POST);
        $repository = new CulinaryDaysRepository();
        $uuid = $repository->insertDeclaration($declaration);
        $_SESSION['culinary-declaration'] = $uuid;

        redirect('/', 303);
    }

    /**
     * @route GET /culinary/cancel-declaration
     */
    public function cancelDeclaration()
    {
        if (isset($_SESSION['culinary-declaration'])) {
            $uuid = $_SESSION['culinary-declaration'];
            $repository = new CulinaryDaysRepository();
            $repository->removeDeclarationByUUID($uuid);
        }

        redirect('/', 204);
    }
}