<?php
namespace App\Controllers;

use App\Core\Session;

/**
 * Feature Controller
 * Handles miscellaneous extra features like Calendar, Meetings, etc.
 */
class FeatureController extends BaseController
{
    /**
     * Show Academic Calendar
     * GET /calendar
     */
    public function calendar(): void
    {
        $this->setTitle('Academic Calendar');
        $this->setBreadcrumbs([
            ['label' => 'Dashboard', 'url' => url('/dashboard')],
            ['label' => 'Calendar']
        ]);

        $this->render('pages/calendar', [
            'pageTitle' => 'Academic Calendar',
        ]);
    }

    /**
     * Show Live Meetings
     * GET /meetings
     */
    public function meetings(): void
    {
        $this->setTitle('Live Meetings');
        $this->setBreadcrumbs([
            ['label' => 'Dashboard', 'url' => url('/dashboard')],
            ['label' => 'Live Meetings']
        ]);

        $this->render('pages/meetings', [
            'pageTitle' => 'Live Meetings',
        ]);
    }
}
