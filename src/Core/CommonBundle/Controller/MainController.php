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

//        $record->setIpAddress($client_ip);

        $client_ip = $request->getClientIp();

        $record_obj = new Record();

        $record_obj -> setId($session->get('PHPSESSID'));
        $record_obj -> setIpAddress($client_ip);
        $record_obj -> setPhpSessionId($session->get('PHPSESSID'));


        /**
         * Test case id
         */
        $test_case_id = 0;

        # Record doesn't exist!
        if(!$record)
        {
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
                $test_case_vector_category_obj ->setId($vector_category_key);
                $test_case_vector_category_obj ->setName($test_case_vector_category);

                # Test case html element's
                foreach ($test_case_html_elements  as $html_element_key => $test_case_html_element) {

                    $test_case_html_element_obj = new HtmlElement();
                    $test_case_html_element_obj ->setId($html_element_key);
                    $test_case_html_element_obj ->setName($test_case_html_element);

                    # Test case event's
                    foreach ($test_case_events as $event_key =>  $test_case_event) {

                        $test_case_event_obj = new Event();
                        $test_case_event_obj ->setId($event_key);
                        $test_case_event_obj ->setName($test_case_event);


                        $test_case_input_vector_obj = new InputVector();
                        $test_case_input_vector_obj ->setId($html_element_key);
                        $test_case_input_vector_obj ->setEvent($test_case_event_obj);
                        $test_case_input_vector_obj ->setHtmlElement($test_case_html_element_obj);
                        $test_case_input_vector_obj ->setVectorCategory($test_case_vector_category_obj);

                        # Test case method's
                        foreach ($test_case_methods as $method_key => $test_case_method) {

                            # Test case request method obj
                            $test_case_method_obj = new TestCaseMethod();
                            $test_case_method_obj ->setId($method_key);
                            $test_case_method_obj ->setName($test_case_method);

                            # Test case description's
                            foreach ($test_case_descriptions as $description_key => $test_case_description) {

                                # Test case description obj
                                $test_case_description_obj = new TestCaseDescription();
                                $test_case_description_obj -> setId($description_key); # OBJ ID
                                $test_case_description_obj -> setContent($test_case_description);

                                #Test case id:

                                $test_case = new TestCase();
                                $test_case -> setId($test_case_id);
                                $test_case -> setTestCaseDescription($test_case_description_obj);
                                $test_case -> setInputVector($test_case_input_vector_obj);
                                $test_case -> setMethod($test_case_method_obj);
                                $test_case -> setRecord($record_obj);

                                $session->set('record',$record);
                                $session->set('test_case_'.$test_case_id,$test_case);

                                #Test case id ++
                                $test_case_id++;

                            } # Test case descriptions end

                        } # Test case methods && Test case end

                    }

                }

            }


        } # Endif

        exit;


        # Client Ip
        $client_ip = $request->getClientIp();

        # Create new Record object!
        $record = new Record();

        # Set params 4 object!
        $record->setPhpSessionId($session->get('PHPSESSID'));
        $record->setIpAddress($client_ip);

        # Let's set record object to session
        //$session->set('record',$record);

//
//        echo "<pre>";
//        var_dump($session->get('record')); # See result
//        echo "</pre>";
//
//        # Attach record object from session to variable record!
//        $record = $session->get('record');
//
//        # Then record variable merge Record object!
//        $record = $em->merge($record);
//
//        # Result!
//        echo $record->getIpAddress();

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
