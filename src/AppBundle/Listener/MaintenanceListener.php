<?php
/**
 * Created by PhpStorm.
 * User: Human Booster
 * Date: 27/06/2017
 * Time: 10:25
 */

namespace AppBundle\Listener;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class MaintenanceListener
{
    protected $templating, $active;

    function __construct(TwigEngine $template, $active)
    {
        $this->templating = $template;
        $this->active = $active;
    }

    protected function maintenance(Response $response)
    {
        $html = $this->templating->render('maintenance/maintenance.html.twig');
        $response->setContent($html);

        return $response;
    }
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            // don't do anything if it's not the master request
          //  dump('isMasterRequest');
            return;
        }
        if ($this->active) {
            $response = $this->maintenance(new Response());
            // Send the modified response object to the event
            $event->setResponse($response);
        }

     //   dump('onKernelRequest');
    }
}
