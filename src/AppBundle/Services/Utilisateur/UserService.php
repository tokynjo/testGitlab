<?php
/**
 * Created by PhpStorm.
 * User: jocelyn
 * Date: 5/9/18
 * Time: 10:56 AM
 */
namespace AppBundle\Services\Utilisateur;


use AppBundle\Services\ContainerService;

class UserService
{

    public $container;
    public $em;

    public function __construct(ContainerService $containerService )
    {
        $this->container = $containerService->container;
        $this->em = $containerService->em;
    }

    public function getUtilisateur($data ){

        if($this->testExistance('email',trim($data['email']))){
            $message = 'Email existant: Veuillez changer l\'adresse email';
        }else{
            $token = $this->upDateUser($data);
            $this->container->get('app.tools')->sendMailRegister($data,$token,'Création de compte');
            $message = 'Merci! : Un email de confirmation a été envoyé vers votre compte';
        }

        $res = [
            'message'=> $message,
            'email'=>$data['email']
        ];

        return $res;

    }

    public function miseAjourUser($data){

    }

    public function listingUser($data){

    }


    public function loginUser($data){

        $username = trim($data['username']);
        $password = trim($data['password']);

        if($username != ''){
            $userManager = $this->container->get('fos_user.user_manager');

            $user = $userManager->findUserByUsernameOrEmail($username);
            if($user == null){
                $view = [
                    'response'=>'Nom d\'utilisateur incorrect',
                    'status'=>false
                ];

                return $view;
            }

            $isValid = $this->container->get('security.password_encoder')
                ->isPasswordValid($user, $password);

            if ($isValid) {
                if($user->isEnabled() == 1){
                    $response = 'OK';
                    $status = true;
                }else{
                    $response = 'Votre compte est expirée !';
                    $status = false;
                }

                $view = [
                    'response'=>$response,
                    'id'=>$user->getId(),
                    'status'=>$status
                ];
            }else{
                $view = [
                    'response'=>'Mot de passe incorrect',
                    'status'=>false
                ];
            }


        }else{
            $view = [
                'response'=>'Remplir le nom d\'utilisateur',
                'status'=>false
            ];
        }

        return $view;
    }

    private function testExistance($propriete,$value){

        $user = $this->em->getRepository('AppBundle:User')->findOneBy([$propriete => $value]);

        if($user){
            return true;
        }else{
            return false;
        }
    }

    private function upDateUser($data){

        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->createUser();
        $user->setNom($data['nom']);
        $user->setPrenom($data['prenom']);
        $user->setUsername($data['prenom']);
        $user->setTel($data['tel']);
        $user->setType('expert');

        $user->setEnabled(false);
        $user->setEmail($data['email']);
        $user->setPlainPassword(md5(uniqid()));
        $user->setConfirmationToken(md5(uniqid()));
        $user->addRole("ROLE_ADMIN");
        $userManager->updateUser($user);
        $token = $user->getConfirmationToken();


        return $token;

    }



}