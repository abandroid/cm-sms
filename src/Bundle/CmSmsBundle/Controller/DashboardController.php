<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\CmSms\Bundle\CmSmsBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/")
 */
class DashboardController extends Controller
{
    /**
     * @Route("/", name="endroid_cm_sms_dashboard")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        // Always disable web profiler when using React
        if ($this->has('profiler')) {
            $this->get('profiler')->disable();
        }

        return [];
    }
}
