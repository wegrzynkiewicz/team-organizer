<?php

declare(strict_types=1);

namespace RedCart\TeamOrganizer\Culinary;

use RedCart\TeamOrganizer\Culinary\Exception\InvalidDeclaration;
use RedCart\TeamOrganizer\Foundation\AbstractController;

class CulinaryController extends AbstractController
{
    /**
     * @route GET /
     */
    public function index()
    {
        $out['title'] = 'Kulinarne piątki';

        $repository = new CulinaryDaysRepository();
        $declarations = $repository->getDeclarations();

        $out['undeclaredEmployees'] = $this->getUndeclaredEmployee($declarations);
        $out['projections'] = $this->getProjections($declarations);

        try {
            $out['request'] = $repository->getDeclarationByUUID($_SESSION['culinary-declaration'])->toArray();
            $out['alreadyDeclared'] = true;
        } catch(InvalidDeclaration $exception) {
            // nothing
        }

        $this->get('twig')->display('culinary/declarations.twig', $out);
    }

    /**
     * @route POST /
     */
    public function replaceDeclaration()
    {
        $declaration = new Declaration($_POST);
        $repository = new CulinaryDaysRepository();

        if ($repository->hasDeclarationByUUID($_SESSION['culinary-declaration'] ?? '')) {
            $repository->updateDeclarationByUUID($declaration, $_SESSION['culinary-declaration']);
        } else {
            $uuid = $repository->insertDeclaration($declaration);
            $_SESSION['culinary-declaration'] = $uuid;
        }

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

    /**
     * Zwraca zgrupowane deklaracje po restauracji
     *
     * @param $declarations Declaration[]
     * @return array
     */
    protected function getProjections($declarations): array
    {
        $projections = [];
        foreach ($declarations as $declaration) {
            $projections[$declaration->getRestaurant()][] = $declaration;
        }

        $projections = array_map(function ($declarations, $restaurant) {
            $projection = new Projection($restaurant);
            $projection->addDeclarations($declarations);
            return $projection;
        }, $projections, array_keys($projections));

        return $projections;
    }

    /**
     * Zwraca tablicę pracowników którzy jeszcze się niezadeklarowali
     *
     * @param $declarations Declaration[]
     * @return array
     */
    protected function getUndeclaredEmployee($declarations): array
    {
        $undeclaredEmployees = $this->get('config')['employees'];
        foreach ($declarations as $declaration) {
            foreach ($declaration->getPersons() as $person) {
                if (in_array($person, $undeclaredEmployees)) {
                    $key = array_search($person, $undeclaredEmployees);
                    unset($undeclaredEmployees[$key]);
                }
            }
        }

        return $undeclaredEmployees;
    }
}