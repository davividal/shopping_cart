<?php
/**
 * Created by PhpStorm.
 * User: davi
 * Date: 26.11.16
 * Time: 17:48
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends Controller
{
    /**
     * @Route("/dashboard/", name="user-dashboard")
     */
    public function dashboardAction()
    {
        $user = $this->getUser();

        return $this->render(
            'home-broker/dashboard.html.twig',
            [
                'user' => $user,
                'stocks' => $user->getTrades()
            ]
        );
    }
}