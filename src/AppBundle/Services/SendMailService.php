<?php
/**
 * Created by PhpStorm.
 * User: JOCELYN
 * Date: 12/07/2017
 * Time: 16:18
 */

namespace AppBundle\Services;


use Symfony\Component\DependencyInjection\ContainerInterface;

class SendMailService
{

    private $container;
    private $mailer;

    public function __construct(ContainerInterface $container,\Swift_Mailer $mailer)
    {
        $this->container = $container;
        $this->mailer = $mailer;
    }


    public function sendMail($mailFrom,$mailTo,$subject,$body){


        $message = \Swift_Message::newInstance()
            ->setFrom($mailFrom)
            ->setTo($mailTo)
            ->setSubject($subject)
            ->setContentType('text/html')
            ->setBody($body);


        $sent = $this->mailer->send($message);

        $transport = $this->mailer->getTransport();

        if ($transport instanceof \Swift_Transport_SpoolTransport) {
            $spool = $transport->getSpool();
            $spool->flushQueue($this->container->get('swiftmailer.transport.real'));
//            $spool->flushQueue($transport);
        }
    }



}