<?php
/**
 * Created by PhpStorm.
 * User: JO
 * Date: 03/04/2018
 * Time: 18:07
 */

namespace AppBundle\Services;

use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View;
use Symfony\Component\DependencyInjection\ContainerInterface; // Utilisation de la vue de FOSRestBundle

class ToolsService
{

    public $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getDataJson($request){
        $data = $request->getContent();
        $tab = json_decode($data, true);

        return $tab;
    }

    public function sendDataJson($data){
        $view = View::create($data);
        $view->setFormat('json');
        $view->setHeader('Access-Control-Allow-Origin', '*');

        return $view;
    }


    public function getEncodeNbArticle($nbArticle){

        $nbArticle = uniqid().'2$'.base64_encode($nbArticle).'3$'.uniqid();

        return $nbArticle;
    }


    public function getDecodeNbArticle($nbArticleEncode){

        $tab = explode('3$',$nbArticleEncode);
        $tab1 = explode('2$',$tab[0]);

        $nbArticle = (int)base64_decode($tab1[1]);

        return $nbArticle;
    }

    public function getStrtotimeEncode($nbJour){

        $date = new \DateTime();
        $_1jour = 86400;
        $dateFormat = date_format($date,'d-m-Y');
        $sttNow = strtotime($dateFormat);
        $sttNew = $sttNow + $_1jour * $nbJour;
        $sttNewEncode = base64_encode($sttNew).'3$'.uniqid();

        $tab = explode('3$',$sttNewEncode);

        $codeLimiteTmt = date('d-m-Y',base64_decode($tab[0]));

        return [$sttNewEncode,$codeLimiteTmt] ;
    }



    public function getUserDirectory($companyName){

        $directory = __DIR__.'/../../../'.$this->container->getParameter('user_directory') . '/'.$companyName. '/';
        return $directory;
    }

    public function sendMailRegister($data,$token,$subject)
    {
        //        $mailFrom = $this->container->getParameter('mailer_user');
        $mailFrom = $this->container->getParameter('no_reply_address');
        $mailTo = $data['email'];
        $prenom = $data['prenom'];
        $tamplate = ':email:registerEmail.html.twig';

        $param    = array(
            'subject'  => $subject,
            'mailto' => $mailTo,
            'mailfrom' => $mailFrom,
            'nom' => $prenom,
            'token' => $token,
            'type' => 'Creation'
        );

        $body = $this->container->get('templating')->render($tamplate,$param);
        $this->container->get('app.mailer')->sendMail($mailFrom,$mailTo,$subject,$body);
    }

}