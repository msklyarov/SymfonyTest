<?php

namespace BusinessBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use BusinessBundle\Entity\Affiliate;
use BusinessBundle\Form\AffiliateType;

/**
 * Affiliate controller.
 *
 * @Route("/affiliate")
 */
class AffiliateController extends Controller
{
    /**
     * Lists all Affiliate entities.
     *
     * @Route("/", name="affiliate_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $affiliates = $em->getRepository('BusinessBundle:Affiliate')->findAll();

        return $this->render('affiliate/index.html.twig', array(
            'affiliates' => $affiliates,
        ));
    }

    /**
     * Creates a new Affiliate entity.
     *
     * @Route("/new", name="affiliate_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $affiliate = new Affiliate();
        $form = $this->createForm('BusinessBundle\Form\AffiliateType', $affiliate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($affiliate);
            $em->flush();

            return $this->redirectToRoute('affiliate_show', array('id' => $affiliate->getId()));
        }

        return $this->render('affiliate/new.html.twig', array(
            'affiliate' => $affiliate,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Affiliate entity.
     *
     * @Route("/{id}", name="affiliate_show")
     * @Method("GET")
     */
    public function showAction(Affiliate $affiliate)
    {
        $deleteForm = $this->createDeleteForm($affiliate);

        return $this->render('affiliate/show.html.twig', array(
            'affiliate' => $affiliate,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Affiliate entity.
     *
     * @Route("/{id}/edit", name="affiliate_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Affiliate $affiliate)
    {
        $editForm = $this->createForm('BusinessBundle\Form\AffiliateType', $affiliate);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($affiliate);
            $em->flush();

            return $this->redirectToRoute('affiliate_edit', array('id' => $affiliate->getId()));
        }

        return $this->render('affiliate/edit.html.twig', array(
            'affiliate' => $affiliate,
            'edit_form' => $editForm->createView(),
        ));
    }
}
