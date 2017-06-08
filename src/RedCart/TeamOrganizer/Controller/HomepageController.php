<?php

declare(strict_types=1);

namespace RedCart\TeamOrganizer\Controller;

use RedCart\TeamOrganizer\Foundation\AbstractController;

class HomepageController extends AbstractController
{
    /**
     * @route GET /
     */
    public function index()
    {
        redirect('/dishwasher', 303);
    }
}