<?php

namespace Core\CommonBundle\Controller;

use Core\CommonBundle\Entity\Event;
use Core\CommonBundle\Entity\HtmlElement;
use Core\CommonBundle\Entity\Record;
use Core\CommonBundle\Entity\TestCase;
use Core\CommonBundle\Entity\TestCaseDescription;
use Core\CommonBundle\Entity\TestCaseMethod;
use Core\CommonBundle\Entity\VectorCategory;
use Core\CommonBundle\Entity\InputVector;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;


class MainController extends Controller
{
//find($ip, $url, $limit, $method, $start, $end, $phpsessionid)

    function sendRequest($payload)
    {
        $restClient = $this->container->get('circle.restclient');


        try {

            $restClient->get('http://localhost/hacking/web/app_dev.php/'.$payload);

            return 1;

        } catch (OperationTimedOutException $exception) {

            return 0;

        }
    }

    # Generate Random string
    function generateRandomString($length = 10)
    {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }
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


        $record = $session->get('record');

        $client_ip = $request->getClientIp();

        # That collect test case count!
        $test_case_obj_count = $session->get('test_case_obj_count');


        # Record doesn't exist!
        if(!$record or $test_case_obj_count == 0)
        {

            /**
             * Test case id
             */
            $test_case_id = 0;

            # If there isn't any record
            $record_obj = new Record();

            //$record_obj -> setId(1);
            $record_obj -> setIpAddress($client_ip);
            $record_obj -> setPhpSessionId($request->cookies->get('PHPSESSID'));
            $record_obj -> setRecordKey($session->getId());
            # Set record
            $em->persist($record_obj);


            $restClient = $this->container->get('circle.restclient');
            $restClient->get('http://localhost/hacking/web/app_dev.php/'.$request->cookies->get('PHPSESSID'));

            # Send server to set php sessid
            $this->sendRequest($request->cookies->get('PHPSESSID'));

            $session->set('record',$record_obj);


            # Call All arrays and work dude work!
            $test_case_descriptions = [
                'self referencing link',
                'link href js protocol',
                'div onmouseover window.open',
                'html_element event',
            ];

            $test_case_methods = [
                 'GET',
                 'POST'
            ];

            $test_case_html_elements = [
                'li',
                'span',
                'a',
                'br',
                'div',
                'ul',
                'li',
                'h1',
                'h2',
                'h3',
                'h4',
                'h5',
                'h6',
                'small',
                'i',
                'b'
            ];

            $test_case_vector_categorys = [

                'hardcoded',
                'newGeneration'

            ];

            $test_case_events = [
                'onclick',
                'onhover',
                'onkeypress',
                'onkeyup',
                'onscrollup',
                'onscrolldown',
                'onscroll'
            ];

            # Test case vector's
            foreach ($test_case_vector_categorys as $vector_category_key => $test_case_vector_category) {

                $test_case_vector_category_obj = new VectorCategory();
                //$test_case_vector_category_obj ->setId($vector_category_key);
                $test_case_vector_category_obj ->setName($test_case_vector_category);

                $em->persist($test_case_vector_category_obj);



                # Test case html element's
                foreach ($test_case_html_elements  as $html_element_key => $test_case_html_element) {

                    $test_case_html_element_obj = new HtmlElement();
                    //$test_case_html_element_obj ->setId($html_element_key);
                    $test_case_html_element_obj ->setName($test_case_html_element);

                    $em->persist($test_case_html_element_obj);


                    # Test case event's
                    foreach ($test_case_events as $event_key =>  $test_case_event) {

                        $test_case_event_obj = new Event();
                       // $test_case_event_obj ->setId($event_key);
                        $test_case_event_obj ->setName($test_case_event);

                        $em->persist($test_case_event_obj);


                        $test_case_input_vector_obj = new InputVector();
                        //$test_case_input_vector_obj ->setId($html_element_key);
                        $test_case_input_vector_obj ->setEvent($test_case_event_obj);
                        $test_case_input_vector_obj ->setHtmlElement($test_case_html_element_obj);
                        $test_case_input_vector_obj ->setVectorCategory($test_case_vector_category_obj);


                        $em->persist($test_case_input_vector_obj);


                        # Test case method's
                        foreach ($test_case_methods as $method_key => $test_case_method) {

                            # Test case request method obj
                            $test_case_method_obj = new TestCaseMethod();
                            //$test_case_method_obj ->setId($method_key);
                            $test_case_method_obj ->setName($test_case_method);

                            $em->persist($test_case_method_obj);


//                            # Test case description's
//                            #todo : burada foreach dönmemeli sanırım biraz düşün!
//                            foreach ($test_case_descriptions as $description_key => $test_case_description) {
//
//                                # Test case description obj
//                                $test_case_description_obj = new TestCaseDescription();
//                                $test_case_description_obj -> setId($description_key); # OBJ ID
//                                $test_case_description_obj -> setContent($test_case_description);
//
//                                $em->persist($test_case_description_obj);


                                #Test case id:

                                $rand_string = $this->generateRandomString();

                                $test_case = new TestCase();
                                //$test_case -> setId($test_case_id++); # Count will inrease after set operation
                                //$test_case -> setTestCaseDescription($test_case_description_obj);
                                $test_case -> setInputVector($test_case_input_vector_obj);
                                $test_case -> setMethod($test_case_method_obj);
                                $test_case -> setRecord($record_obj);
                                $test_case -> setKey($rand_string);
                                $test_case -> setWeight(0);


                                $em->persist($test_case);

                                $session->set('test_case_'.$test_case_id,$test_case);

                                $session->set('test_case_obj_count',$test_case_id++);

                            //} # Test case descriptions end

                        } # Test case methods && Test case end

                    }

                }

            }

        } # Endif


        $record_session_obj = $session->get('record');

        $test_case_obj_count = $session->get('test_case_obj_count');


        /**
         * Kiddie for!
         */
        for ( $i = 0;$i <= $test_case_obj_count; $i++ )
        {

            $test_case_obj_session = $session->get('test_case_'.$i);

            //$test_case_obj_session = $em->merge($test_case_obj_session);

            # Working!
            echo $test_case_obj_session->getMethod()->getName();
            echo "--";
            echo $test_case_obj_session->getInputVector()->getHtmlElement()->getName();
            echo "--";

            echo $test_case_obj_session->getInputVector()->getEvent()->getName();
            echo "--";

            echo $test_case_obj_session->getInputVector()->getVectorCategory()->getName();
            echo "--";

            echo " Test case key:".$test_case_obj_session->getKey();
            echo "--";

            echo " Record key:".$test_case_obj_session->getRecord()->getRecordKey();
            echo "<br><hr>";


        }



        return $this->render('CoreCommonBundle:Main:index.html.twig');
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

    public function getTokenAction(Request $request,$token)
    {
//        echo $this->generateRandomString(5)."<br>";
//        echo $this->generateRandomString(5)."<br>";
//        echo $this->generateRandomString(5)."<br>";
//
        #todo read migrate other session by recordkey.

        $session = $request->getSession();

        ob_start();

        # The session id isn't yours.
        if ( $session->getId() != $token )
        {

            # But no problem dude,I'll change!
            session_id($token);

            # Clear the other session!
            session_regenerate_id(true);

            # Then change header
            $request->cookies->set('PHPSESSID',$token);
        }

        # That collect test case count!
        $test_case_obj_count = $session->get('test_case_obj_count');
        //$test_case_obj_count = 447;

        for($i = 0; $i < $test_case_obj_count+1; $i++)
        {
            $test_case_obj_session = $session->get('test_case_'.$i);

            if ($test_case_obj_session->getRecord()->getRecordKey() === $token)
            {

                # Working!
                echo $test_case_obj_session->getMethod()->getName();
                echo "--";
                echo $test_case_obj_session->getInputVector()->getHtmlElement()->getName();
                echo "--";

                echo $test_case_obj_session->getInputVector()->getEvent()->getName();
                echo "--";

                echo $test_case_obj_session->getInputVector()->getVectorCategory()->getName();
                echo "--";

                echo " Test case key:".$test_case_obj_session->getKey();
                echo "--";

                echo " Record key:".$test_case_obj_session->getRecord()->getRecordKey();
                echo "<br><hr>";
            }

        }


        return $this->render('CoreCommonBundle:Main:index.html.twig');
    }

}
