<?php

namespace Core\CommonBundle\Controller;

use Core\CommonBundle\Entity\TestCase;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SpecialTestCaseController extends Controller
{

    # Generate Random string
    function generateRandomString($length = 10)
    {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }


    public function setSpecialTestCaseAction(Request $request)
    {

        $session = $request->getSession();

        # That collect test case count!
        $record = $session->get('record');

//        print_r($record);


        /**
         * Call doctrine manager
         */
        $em = $this->getDoctrine()->getManager();

        # Record obj
        $record_obj = $em->getRepository('CoreCommonBundle:Record')->findOneBy(array('recordKey'=>$record->getRecordKey()));


        # Call entity
        $test_case = new TestCase();

        # Set params
        $test_case->setKey($this->generateRandomString(10));
        $test_case->setRecord($record_obj);
        $test_case->setWeight(0);
        $test_case->setSpecial(1);

        echo $counter = $session->get('test_case_obj_count');
        echo "<br>";
        $session->set('test_case_obj_count', ++$counter);




        $em->persist($test_case);


        echo $session->get('test_case_obj_count');
        # todo Done other jobs ex: charts, map
        exit;

        return $test_case;

    }
}
