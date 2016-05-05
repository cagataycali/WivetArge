<?php

namespace Core\CommonBundle\Controller;

use Buzz\Browser;
use Core\CommonBundle\Entity\Event;
use Core\CommonBundle\Entity\HtmlElement;
use Core\CommonBundle\Entity\Record;
use Core\CommonBundle\Entity\TestCase;
use Core\CommonBundle\Entity\TestCaseDescription;
use Core\CommonBundle\Entity\TestCaseMethod;
use Core\CommonBundle\Entity\VectorCategory;
use Core\CommonBundle\Entity\InputVector;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\StreamedResponse;


class MainController extends Controller
{
//find($ip, $url, $limit, $method, $start, $end, $phpsessionid)


    /**
     * @param Request $request
     * @param $token
     * @return JsonResponse
     */
    public function getTestCasesApiAction(Request $request,$token)
    {
        return new JsonResponse($this->getTestCases($request));
    }

    /**
     * @param Request $request
     * @param $token
     * @return JsonResponse
     * This method working for external users.
     */
    public function externalTestCasesApiAction(Request $request, $token)
    {
        # Migrate session
        $this->migrateSession($request,$token);

        return new JsonResponse($this->getTestCases($request));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {

        #Doctrine
        $em = $this->getDoctrine()->getManager();

        # Get test case count
        list($session, $test_case_obj_count) = $this->testCaseCount($request);

        # Generate test cases or die dude!
        $this->generateTestCases($request, $session, $test_case_obj_count, $em);

        return $this->render('CoreCommonBundle:Main:index.html.twig',array('phpsessid'=>$request->cookies->get('PHPSESSID')));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function testAction(Request $request)
    {
        return $this->render('CoreCommonBundle:Main:test.html.twig',array('test_cases'=> $this->getTestCases($request)));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function getTokenAction(Request $request)
    {

        $token = $request->request->get('token');

        # Migrate session
        $this->migrateSession($request,$token);

        # Redirect test case results
        return $this->redirectToRoute('core_common_homepage');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function successAction(Request $request)
    {
        $test_case_token = $request->request->get('token');
        $token = $request->request->get('event_token');

        # Explode param
        $test_case_token = explode("_",$test_case_token);
        $token = explode("_",$token);


        $test_case_key      = $test_case_token[0];
        $record_key         = $test_case_token[1];

        $input_vector_name  = $token[0];
        $event_name         = $token[1];
//        Todo : input vector and event name validate!

        $response = $this->getTestCaseSuccess($request,$record_key,$test_case_key);

        return new JsonResponse($response);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * Return test cases
     */
    public function resultsAction(Request $request)
    {
        return $this->render('CoreCommonBundle:Main:results.html.twig',array('test_cases'=>$this->getTestCases($request)));
    }


    ### Methods ####                ### Methods ####                ### Methods ####                ### Methods ####


    /**
     * @param Request $request
     * @return array
     */
    function testCaseCount(Request $request)
    {
        $session = $request->getSession();

        # That collect test case count!
        $test_case_obj_count = $session->get('test_case_obj_count');

        return array($session, $test_case_obj_count);
    }

    /**
     * @param $record_key
     * @param $test_case_key
     * @param $test_case_obj_count
     * @param $session
     * @return array
     */
    function testCaseSuccess($record_key, $test_case_key, $test_case_obj_count, $session)
    {

        $time_bundle = $this->getTimeBundle();

        $response = array();

        for ($i = 0; $i < $test_case_obj_count + 1; $i++) {

            $test_case_obj_session = $session->get('test_case_' . $i);

            # If everything OK
            if (
                $test_case_obj_session->getRecord()->getRecordKey() === $record_key &&
                $test_case_obj_session->getKey() === $test_case_key
            ) {

                # Working!
                $response["method"] = $test_case_obj_session->getMethod()->getName();
                $response["html_element_name"] = $test_case_obj_session->getInputVector()->getHtmlElement()->getName();
                $response["event"] = $test_case_obj_session->getInputVector()->getEvent()->getName();
                $response["category"] = $test_case_obj_session->getInputVector()->getVectorCategory()->getName();
                $response["test_case_key"] = $test_case_obj_session->getKey();
                $response["record_key"] = $test_case_obj_session->getRecord()->getRecordKey();
                $response["weight"] = $test_case_obj_session->getWeight();

//                $test_case_obj_session = new TestCase();

                if($test_case_obj_session->getClickCount() == 0)
                {
                    $test_case_obj_session->setFirstClickedAt(new \DateTime("now"));
                }

                $click_count = $test_case_obj_session->getClickCount() + 1;
                $test_case_obj_session->setClickCount($click_count);
                $test_case_obj_session->setLastClickedAt(new \DateTime("now"));

                $response["click_count"] = $test_case_obj_session->getClickCount();
                $response["first_click"] = $time_bundle->diff($test_case_obj_session->getFirstClickedAt());
                $response["last_click"] = $time_bundle->diff( $test_case_obj_session->getLastClickedAt());
            }
        }
        return $response;
    }

    /**
     * @return mixed
     */
    function test_case_descriptions()
    {
        $test_case_descriptions = [
            'self referencing link',
            'link href js protocol',
            'div onmouseover window.open',
            'html_element event',
        ];
        return $test_case_descriptions;
    }

    /**
     * @return array
     */
    function test_case_methods()
    {
        $test_case_methods = [
            'GET',
            'POST'
        ];
        return $test_case_methods;
    }

    /**
     * @return array
     */
    function test_case_html_elements()
    {
        $test_case_html_elements = [
            'li',
            'span',
            'a',
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
            'b',
            'strong'
        ];
        return $test_case_html_elements;
    }

    /**
     * @return array
     */
    function test_case_vector_categorys()
    {
        $test_case_vector_categorys = [

            'hardcoded',
            'newGeneration'

        ];
        return $test_case_vector_categorys;
    }

    /**
     * @return array
     */
    function test_case_events()
    {
        $test_case_events = [
            'onclick',
            'onhover',
            'onkeypress',
            'onkeyup',
            'onmouseout'
        ];
        return $test_case_events;
    }

    /**
     * @param Request $request
     * @param $session
     * @param $test_case_obj_count
     * @param $em
     */
    function generateTestCases(Request $request, $session, $test_case_obj_count, $em)
    {
        $record = $session->get('record');

        # Client ip
        $client_ip = $request->getClientIp();


        # Record doesn't exist!
        if (!$record or $test_case_obj_count == 0) {

            /**
             * Test case id
             */
            $test_case_id = 0;

            $record_obj = $this->setRecordObj($request, $session, $em, $client_ip);


            $session->set('record', $record_obj);


            # Call All arrays and work dude work!
            $test_case_descriptions = $this->test_case_descriptions();

            $test_case_methods = $this->test_case_methods();

            $test_case_html_elements = $this->test_case_html_elements();

            $test_case_vector_categorys = $this->test_case_vector_categorys();

            $test_case_events = $this->test_case_events();

            # Test case vector's
            foreach ($test_case_vector_categorys as $vector_category_key => $test_case_vector_category) {

                $test_case_vector_category_obj = $this->setVectorCategoryObj($em, $test_case_vector_category);

                # Test case html element's
                foreach ($test_case_html_elements as $html_element_key => $test_case_html_element) {

                    $test_case_html_element_obj = $this->setTestCaseHtmlElementObj($em, $test_case_html_element);


                    # Test case event's
                    foreach ($test_case_events as $event_key => $test_case_event) {

                        $test_case_event_obj = $this->setTestCaseEventObj($em, $test_case_event);


                        $test_case_input_vector_obj = $this->setTestCaseInputVectorObj($em, $test_case_event_obj, $test_case_html_element_obj, $test_case_vector_category_obj);


                        # Test case method's
                        foreach ($test_case_methods as $method_key => $test_case_method) {

                            $test_case_method_obj = $this->setTestCaseMethodObj($em, $test_case_method);


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

                            $test_case = $this->setTestCase($em, $test_case_input_vector_obj, $test_case_method_obj, $record_obj, $rand_string);

                            $session->set('test_case_' . $test_case_id, $test_case);

                            $session->set('test_case_obj_count', $test_case_id++);


                            //} # Test case descriptions end

                        } # Test case methods && Test case end

                    }

                }

            }

            # Send request external API :)
            $this->sendRequest($record_obj);

        } # Endif
    }

    /**
     * @param $em
     * @param $test_case_vector_category
     * @return VectorCategory
     */
    function setVectorCategoryObj($em, $test_case_vector_category)
    {
        $test_case_vector_category_obj = new VectorCategory();
        //$test_case_vector_category_obj ->setId($vector_category_key);
        $test_case_vector_category_obj->setName($test_case_vector_category);

        $em->persist($test_case_vector_category_obj);
        return $test_case_vector_category_obj;
    }

    /**
     * @param $em
     * @param $test_case_html_element
     * @return HtmlElement
     */
    function setTestCaseHtmlElementObj($em, $test_case_html_element)
    {
        $test_case_html_element_obj = new HtmlElement();
        //$test_case_html_element_obj ->setId($html_element_key);
        $test_case_html_element_obj->setName($test_case_html_element);

        $em->persist($test_case_html_element_obj);
        return $test_case_html_element_obj;
    }

    /**
     * @param $em
     * @param $test_case_event
     * @return Event
     */
    function setTestCaseEventObj($em, $test_case_event)
    {
        $test_case_event_obj = new Event();
        // $test_case_event_obj ->setId($event_key);
        $test_case_event_obj->setName($test_case_event);

        $em->persist($test_case_event_obj);
        return $test_case_event_obj;
    }

    /**
     * @param $em
     * @param $test_case_event_obj
     * @param $test_case_html_element_obj
     * @param $test_case_vector_category_obj
     * @return InputVector
     */
    function setTestCaseInputVectorObj($em, $test_case_event_obj, $test_case_html_element_obj, $test_case_vector_category_obj)
    {
        $test_case_input_vector_obj = new InputVector();
        //$test_case_input_vector_obj ->setId($html_element_key);
        $test_case_input_vector_obj->setEvent($test_case_event_obj);
        $test_case_input_vector_obj->setHtmlElement($test_case_html_element_obj);
        $test_case_input_vector_obj->setVectorCategory($test_case_vector_category_obj);


        $em->persist($test_case_input_vector_obj);
        return $test_case_input_vector_obj;
    }

    /**
     * @param $em
     * @param $test_case_method
     * @return TestCaseMethod
     */
    function setTestCaseMethodObj($em, $test_case_method)
    {
# Test case request method obj
        $test_case_method_obj = new TestCaseMethod();
        //$test_case_method_obj ->setId($method_key);
        $test_case_method_obj->setName($test_case_method);

        $em->persist($test_case_method_obj);
        return $test_case_method_obj;
    }

    /**
     * @param $em
     * @param $test_case_input_vector_obj
     * @param $test_case_method_obj
     * @param $record_obj
     * @param $rand_string
     * @return TestCase
     */
    function setTestCase($em, $test_case_input_vector_obj, $test_case_method_obj, $record_obj, $rand_string)
    {
        $test_case = new TestCase();
        //$test_case -> setId($test_case_id++); # Count will inrease after set operation
        //$test_case -> setTestCaseDescription($test_case_description_obj);
        $test_case->setInputVector($test_case_input_vector_obj);
        $test_case->setMethod($test_case_method_obj);
        $test_case->setRecord($record_obj);
        $test_case->setKey($rand_string);
        $test_case->setWeight(0);
        $test_case->setSpecial(0);


        $em->persist($test_case);
        return $test_case;
    }



    /**
     * @param Request $request
     * @param $session
     * @param $em
     * @param $client_ip
     * @return Record
     */
    function setRecordObj(Request $request, $session, $em, $client_ip)
    {
    # If there isn't any record
        $record_obj = new Record();

        //$record_obj -> setId(1);
        $record_obj->setIpAddress($client_ip);
        $record_obj->setPhpSessionId($request->cookies->get('PHPSESSID'));
        $record_obj->setRecordKey($session->getId());
        # Set record
        $em->persist($record_obj);
        return $record_obj;
    }

    /**
     * @param Request $request
     * @return array
     */
    function testCases(Request $request)
    {
        $time_bundle = $this->getTimeBundle();


        list($session, $test_case_obj_count) = $this->testCaseCount($request);
        //$test_case_obj_count = 447;

        $test_cases_array = array();

        # Collect all test inputs
        for ($i = 0; $i < $test_case_obj_count + 1; $i++) {
            $test_case_obj_session = $session->get('test_case_' . $i);

            $test_cases_array[$i]["id"] = $test_case_obj_session->getId();
            $test_cases_array[$i]["method_name"] = $test_case_obj_session->getMethod()->getName();
            $test_cases_array[$i]["input_vector_name"] = $test_case_obj_session->getInputVector()->getHtmlElement()->getName();
            $test_cases_array[$i]["event_name"] = $test_case_obj_session->getInputVector()->getEvent()->getName();
            $test_cases_array[$i]["vector_category_name"] = $test_case_obj_session->getInputVector()->getVectorCategory()->getName();
            $test_cases_array[$i]["test_case_key"] = $test_case_obj_session->getKey();
            $test_cases_array[$i]["record_key"] = $test_case_obj_session->getRecord()->getRecordKey();
            $test_cases_array[$i]["weight"] = $test_case_obj_session->getWeight();
            $test_cases_array[$i]["click_count"] = $test_case_obj_session->getClickCount();
            $test_cases_array[$i]["special"] = $test_case_obj_session->getSpecial();
            $test_cases_array[$i]["first_click"] = $time_bundle->diff($test_case_obj_session->getFirstClickedAt());
            $test_cases_array[$i]["last_click"] = $time_bundle->diff( $test_case_obj_session->getLastClickedAt());

        }
        return $test_cases_array;
    }

    /**
     * @param Request $request
     * @return array
     */
    function getTestCases(Request $request)
    {
        $test_cases_array = $this->testCases($request);

        return $test_cases_array;
    }


    /**
     * @param Request $request
     * @param $record_key
     * @param $test_case_key
     * @return array
     */
    public function getTestCaseSuccess(Request $request,$record_key,$test_case_key)
    {
        list($session, $test_case_obj_count) = $this->testCaseCount($request);

        $response = $this->testCaseSuccess($record_key, $test_case_key, $test_case_obj_count, $session);

        return $response;
    }

//    todo : migrate session doesn't working properly
    /**
     * @param Request $request
     * @param $token
     * @return mixed
     */
    function migrateSession(Request $request,$token)
    {

        $session = $request->getSession();

        ob_start();

        # The session id isn't yours.
        if ( $session->getId() != $token )
        {
            # Destroy session
            session_destroy();

            # Set session
            session_id($token);

            # Regenerate invalid
            session_regenerate_id(false);

            # Session start

            session_start();
            # Then change header
            $request->cookies->set('PHPSESSID',$token);

        }


        return $request->cookies->get('PHPSESSID');
    }

    /**
     * @param $record_obj
     */
    function sendRequest($record_obj)
    {
        $array = array();

        //$output = $this->get('api_caller')->call(new HttpGetJson("http://localhost/hacking/web/app_dev.php/".$record_obj->getIpAddress(), $array));

        # todo : ?
    }

    # Generate Random string
    function generateRandomString($length = 10)
    {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    /**
     * @return mixed
     */
    function getTimeBundle()
    {
        /**
         * Time ago eklentisini kullanabilmek için.
         */
        $time_bundle = $this->container->get('time.templating.helper.time');
        return $time_bundle;
    }
}
