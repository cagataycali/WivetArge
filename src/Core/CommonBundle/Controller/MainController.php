<?php

namespace Core\CommonBundle\Controller;

use Core\CommonBundle\Entity\Event;
use Core\CommonBundle\Entity\Record;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;


class MainController extends Controller
{
//find($ip, $url, $limit, $method, $start, $end, $phpsessionid)

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {

        #Doctrine
        $em = $this->getDoctrine()->getManager();

        #Cookie
        $cookie = $request->cookies;

        # Session
        $session = $request->getSession();

        # Client Ip
        $client_ip = $request->getClientIp();

        # Create new Record object!
        $record = new Record();

        # Set params 4 object!
        $record->setPhpSessionId($session->get('PHPSESSID'));
        $record->setIpAddress($client_ip);

        # Let's set record object to session
        $session->set('record',$record);


        echo "<pre>";
        var_dump($session->get('record')); # See result
        echo "</pre>";

        # Attach record object from session to variable record!
        $record = $session->get('record');

        # Then record variable merge Record object!
        $record = $em->merge($record);

        # Result!
        echo $record->getIpAddress();

        # Return
        return $this->render('CoreCommonBundle:Main:index.html.twig');

//              $data = $this->container->get('profiler')->find('','', 10, '', '','',$session_id);
//        return $this->render('CoreCommonBundle:Main:index.html.twig',array('session'=>$session_id,'data'=>$data[0]["token"]));
    }

    public function testAction(Request $request,$token)
    {
//        $session_id = $request->cookies->get('PHPSESSID');
//
//        $request_uri = $request->getUri();
//        $client_ip = $request->getClientIp();
//        $method = $request->getMethod();
//
//        /**
//         * Eğer bir istemci,
//         * Bu ip adresinden,
//         * Bu url'e
//         *
//         */
//        $data = $this->container->get('profiler')->find($client_ip, '', 10, '', '','',$session_id);
//
//        echo $token."<br>";
//        echo $data[0]['token']."<br>";
//
//        if($data[0]['token'] == $token)
//        {
//            echo "Token aynı!";
//        }
//        else
//        {
//            echo "Token farklı";
//        }
//
//        exit;
//

        /**
         * Eğer kullanıcı saçma bir token gönderdiyse..
         */
        $session_id = $request->cookies->get('PHPSESSID');

        $request_uri = $request->getUri();
        $client_ip = $request->getClientIp();
        $method = $request->getMethod();

        /**
         * Eğer bir istemci,
         * Bu ip adresinden,
         * Bu url'e
         *
         */
        $data = $this->container->get('profiler')->find($client_ip, '', 100, '', '','',$session_id);

        echo $token."<br>";
        echo $data[0]['token']."<br>";

        if($data[0]['token'] == $token)
        {
            echo "Token aynı!";
        }
        else
        {
            echo "Token farklı";
        }

        //todo : list of inputs vectors..
        //return new JsonResponse($yanit);
        return $this->render('CoreCommonBundle:Main:test.html.twig');

    }

}
