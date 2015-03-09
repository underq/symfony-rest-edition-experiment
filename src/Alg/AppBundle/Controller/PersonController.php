<?php

namespace Alg\AppBundle\Controller;

use Alg\AppBundle\Entity\Person;
use Alg\AppBundle\Form\PersonType;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Util\Codes;

class PersonController extends FOSRestController
{

    /**
     * @ApiDoc(
     *   output = "Alg\AppBundle\Entity\Person",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the note is not found"
     *   }
     * )
     *
     * @param Request $request
     * @param $id
     * @return View
     */
    public function getPersonAction(Request $request, $id) {
        $person = $this->getDoctrine()
            ->getRepository('AlgAppBundle:Person')
            ->find($id);

        return new View($person);
    }

    /**
     * @ApiDoc(
     *    resource = true,
     *    input = "Alg\AppBundle\Form\PersonType",
     * )
     * @param Request $request
     * @return array
     */
    public function postPersonAction(Request $request)
    {
        $person = new Person();
        $form = $this->createForm('person', $person);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();

            return $this->routeRedirectView('get_person', array('id' => $person->getId()));
        }

        return array(
            'form' => $form
        );
    }

    /**
     * @ApiDoc(
     *  resource = true,
     *   input = "Alg\AppBundle\Entity\Person",
     *   statusCodes = {
     *     201 = "Returned when a new resource is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     */
    public function putPersonAction(Request $request, $id)
    {
        $person = $this->getDoctrine()
            ->getRepository('AlgAppBundle:Person')
            ->find($id);
        if (false === $person) {
            $person = new Person();
            $statusCode = Codes::HTTP_CREATED;
        } else {
            $statusCode = Codes::HTTP_NO_CONTENT;
        }

        $form = $this->createForm('person', $person);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();


            return $this->routeRedirectView('get_person', array('id' => $person->getId()), $statusCode);
        }

        die(var_dump($form->getErrorsAsString()));

        return $form;
    }

    /**
     * @ApiDoc(
     *   resource = true,
     * )
     * @param Request $request
     * @param $id
     */
    public function deletePersonAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $person = $this->getDoctrine()->getRepository('AlgAppBundle:Person')->find($id);

        $em->remove($person);
        $em->flush();
    }
}
