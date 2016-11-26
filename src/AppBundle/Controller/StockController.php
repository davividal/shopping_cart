<?php
/**
 * Created by PhpStorm.
 * User: davi
 * Date: 26.11.16
 * Time: 16:12
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class StockController extends Controller
{
    /**
     * @Route("/dashboard/stock-options", name="stock-options")
     */
    public function indexAction()
    {
        $stockOptions = $this->get('doctrine')->getRepository('AppBundle:StockOption')->findAll();

        return $this->render(
            'home-broker/stock-list.html.twig',
            [
                'stockOptions' => $stockOptions
            ]
        );
    }
}