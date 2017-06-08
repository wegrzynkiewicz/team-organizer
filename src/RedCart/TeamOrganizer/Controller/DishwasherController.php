<?php

declare(strict_types=1);

namespace RedCart\TeamOrganizer\Controller;

use RedCart\TeamOrganizer\Dishwasher\DishwasherRegister;
use RedCart\TeamOrganizer\Foundation\AbstractController;

class DishwasherController extends AbstractController
{
    /**
     * @route GET /dishwasher
     */
    public function displayContext()
    {
        $out['title'] = "Dyżur wyciągacza ze zmywarki";

        $register = new DishwasherRegister();
        $out['projection'] = $register->getProjection();
        $out['canMark'] = $register->canMarkToday();

        $this->get('twig')->display('views/dishwasher/context.twig', $out);
    }

    /**
     * @route POST /dishwasher
     */
    public function markEmployee()
    {
        $register = new DishwasherRegister();
        $register->mark($_POST['employee']);

        redirect('/dishwasher', 303);
    }
}