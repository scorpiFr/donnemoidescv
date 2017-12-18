<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Region;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\FileCookieJar;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\RequestOptions;

class DefaultController extends Controller
{
    /**
     * @Route("/default", name="defaultpage")
     */
    public function defaultAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/default.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
    /**
     * @Route("/", name="indexpage")
     */
    public function indexAction(Request $request)
    {
        $regions = $this->getDoctrine()->getManager()->getRepository('AppBundle:Region')->getAllSortedByName();

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'regions' => $regions
        ]);
    }


    /**
     * @Route("/ajaxuserrequest/new/getnbrResults", name="ajaxuserrequest_new_getnbrResults")
     */
    public function newrequestAction(Request $request)
    {
        // prise et verification des parametres
        {
            if (!$request->isMethod('POST')) {
                throw $this->createNotFoundException('This page does not exist');
            }
            // get paramters
            {
                $email = $request->request->get('email');
                $keyword = $request->request->get('keyword');
                $regionId = $request->request->get('region');
            }
            // initialisations
            {
                /** @var Region $region */
                $region = $this->getDoctrine()->getManager()->getRepository('AppBundle:Region')->find($regionId);
                $regionApecId = $region->getApecId();
            }
        }

        // prise du nbr resultats
        // $this->container->get('apec_manager')->connect();
        $nbrResults = $this->container->get('apec_manager')->getNbrResults($keyword, $region->getApecId());

        return $this->render('default/confirmerrequest.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'email' => $email,
            'keyword' => $keyword,
            'region' => $region,
            'nbrResults' => $nbrResults,
        ]);
    }


}
