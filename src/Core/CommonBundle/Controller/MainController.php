<?php

namespace Core\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;


class MainController extends Controller
{
//find($ip, $url, $limit, $method, $start, $end, $phpsessionid)
    public function indexAction(Request $request)
    {

        $session_id = $request->cookies->get('PHPSESSID');

        $request_uri = $request->getUri();
        $client_ip = $request->getClientIp();
        $method = $request->getMethod();


        /**
         * Eureka!
         *
         * Benim session id değerim ile otomatik generate olmuş ve istek yapmış son 10 adet request'i görmektesiniz..
         *
         */
//        echo "<pre>";
//        print_r($this->container->get('profiler')->find($client_ip, $request_uri, 10, $method, '','',$session_id));
//        echo "</pre>";
//
//
        $data = $this->container->get('profiler')->find('','', 10, '', '','',$session_id);
//
//        echo "<pre>";
//        print_r($data);
//        echo "</pre>";
//
//
//        echo count($data);
//

        return $this->render('CoreCommonBundle:Main:index.html.twig',array('session'=>$session_id,'data'=>$data[0]["token"]));
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


        return $this->render('CoreCommonBundle:Main:test.html.twig');

    }

}
