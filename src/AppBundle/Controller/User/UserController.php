<?php

namespace AppBundle\Controller\User;

//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class UserController extends Controller
{

    /**
     * @Route("/user/{token}", name="active-user")
     */
    public function userAction(Request $request, $token)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy(["confirmationToken"=>$token]);
        if(!$user){
            return $this->render(':email:info.html.twig');
        }else{
            return $this->render('email/activeUser.html.twig',['token'=>$token]);
        }

    }


    /**
     * @Route("/active-compte", options={"expose"=true}, name="activeCompte")
     * @Method({"POST"})
     */
    public function getBinaryReport(Request $request)
    {

        $password = $request->request->get('password');
        $token = $request->request->get('token');

        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy(["confirmationToken"=>$token]);
        if(!$user){

            $resultat = [
                'message'=>'Votre session de saisi de mot de passe est expirée.',
                'success'=>false
            ];
            return new JsonResponse($resultat, 200);

        }else{

            $user->setPlainPassword($password);
            $user->setEnabled(true);
            $user->setConfirmationToken(md5(uniqid()));

            $directory = $this->get('app.tools')->getUserDirectory($user->getEmail());

            if (!is_dir($directory)) {
                mkdir($directory, 777,true);
                mkdir($directory.'logo', 777,true);
                mkdir($directory.'images', 777,true);
                mkdir($directory.'articles', 777,true);
            }

            $userManager->updateUser($user);


            $resultat = [
                'message'=>'Votre compte est maintenant actif.',
                'success'=>true
            ];
            return new JsonResponse($resultat, 200);

        }




    }

}
