<?php

namespace Xelbot\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class FeedController extends BaseController
{
    /**
     * @Route("/add-feed")
     * @Template()
     */
    public function addFeedAction()
    {
        return [];
    }
}
