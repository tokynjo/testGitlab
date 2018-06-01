<?php

namespace AppBundle\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use FOS\RestBundle\Controller\Annotations\Get; // N'oublons pas d'inclure Get

class ApiUserController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Post("/api/user/create")
     */
    public function createAction(Request $request)
    {
        $tab = $this->get('app.tools')->getDataJson($request);
        $res = $this->get('app.utilisateur')->getUtilisateur($tab);
        $view = $this->get('app.tools')->sendDataJson($res);
        return $view;
    }


    /**
     * @Rest\View()
     * @Rest\Post("/api/user/login")
     */
    public function loginAction(Request $request)
    {
        $tab = $this->get('app.tools')->getDataJson($request);
        $res = $this->get('app.utilisateur')->loginUser($tab);
        $view = $this->get('app.tools')->sendDataJson($res);
        return $view;
    }

    /**
     * @Rest\View()
     * @Rest\Post("/api/user/update")
     */
    public function updateAction(Request $request)
    {
        $tab = $this->get('app.tools')->getDataJson($request);
        $res = $this->get('app.utilisateur')->miseAjourUser($tab);
        $view = $this->get('app.tools')->sendDataJson($res);
        return $view;
    }

    /**
     * @Rest\View()
     * @Rest\Post("/api/user/listing")
     */
    public function listingAction(Request $request)
    {
        $tab = $this->get('app.tools')->getDataJson($request);
        $res = $this->get('app.utilisateur')->listingUser($tab);
        $view = $this->get('app.tools')->sendDataJson($res);
        
        return $view;
    }


}
