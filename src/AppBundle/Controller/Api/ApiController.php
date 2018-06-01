<?php

namespace AppBundle\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use FOS\RestBundle\Controller\Annotations\Get; // N'oublons pas d'inclure Get

class ApiController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Post("/api/test")
     */
    public function testAction(Request $request)
    {

        $tab = $this->get('app.tools')->getDataJson($request);

        $res = [
            'nom'=>$tab['nom'],
            'prenom'=>$tab['prenom']
        ];

        $view = $this->get('app.tools')->sendDataJson($res);
        return $view;

    }



}
