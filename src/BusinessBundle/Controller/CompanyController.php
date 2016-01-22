<?php

namespace BusinessBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use BusinessBundle\Entity\Company;
use BusinessBundle\Form\CompanyType;

/**
 * Company controller.
 *
 * @Route("/company")
 */
class CompanyController extends Controller
{
    /**
     * Lists all Company entities.
     *
     * @Route("/", name="company_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $companies = $em->getRepository('BusinessBundle:Company')->findAll();

        return $this->render('BusinessBundle:company:index.html.twig', array(
            'companies' => $companies,
        ));
    }

    /**
     * Creates a new Company entity.
     *
     * @Route("/new", name="company_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $company = new Company();
        $form = $this->createForm('BusinessBundle\Form\CompanyType', $company, ['allow_extra_fields' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($company);
            $em->flush();

            return $this->redirectToRoute('company_show', array('id' => $company->getId()));
        }

        return $this->render('BusinessBundle:company:new.html.twig', array(
            'company' => $company,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Company entity.
     *
     * @Route("/{id}", name="company_show")
     * @Method("GET")
     */
    public function showAction(Company $company)
    {
        return $this->render('BusinessBundle:company:show.html.twig', array(
            'company' => $company,
        ));
    }

    /**
     * Displays a form to edit an existing Company entity.
     *
     * @Route("/{id}/edit", name="company_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Company $company)
    {
        $deleteDirectorForm = $this->createDeleteDirectorForm($company);
        $deleteAccountantForm = $this->createDeleteAccountantForm($company);
        $editForm = $this->createForm('BusinessBundle\Form\CompanyType', $company, ['allow_extra_fields' => true]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($company);
            $em->flush();

            return $this->redirectToRoute('company_edit', array('id' => $company->getId()));
        }

        return $this->render('BusinessBundle:company:edit.html.twig', array(
            'company' => $company,
            'edit_form' => $editForm->createView(),
            'delete_director_form' => $deleteDirectorForm->createView(),
            'delete_accountant_form' => $deleteAccountantForm->createView(),
        ));
    }

    /**
     * Deletes the director position.
     *
     * @Route("/{id}/deleteDirector", name="company_deleteDirector")
     * @Method("DELETE")
     */
    public function deleteDirectorAction(Request $request, Company $company)
    {
        $form = $this->createDeleteDirectorForm($company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $companies = $em->getRepository('BusinessBundle:Company')
                ->findBy(['id' => $company->getId()]);

            foreach ($companies as $company) {
                $company->setDirector(null);
            }

            $em->flush();
        }

        return $this->redirectToRoute('company_index');
    }

    /**
     * Deletes the chief accountant position.
     *
     * @Route("/{id}/deleteAccountant", name="company_deleteAccountant")
     * @Method("DELETE")
     */
    public function deleteAccountantAction(Request $request, Company $company)
    {
        $form = $this->createDeleteAccountantForm($company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $companies = $em->getRepository('BusinessBundle:Company')
                ->findBy(['id' => $company->getId()]);

            foreach ($companies as $company) {
                $company->setAccountant(null);
            }

            $em->flush();
        }

        return $this->redirectToRoute('company_index');
    }

    /**
     * Creates a form to delete the director position
     *
     * @param Company $company The Company entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteDirectorForm(Company $company)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('company_deleteDirector', array('id' => $company->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Creates a form to delete the chief accountant position
     *
     * @param Company $company The Company entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteAccountantForm(Company $company)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('company_deleteAccountant', array('id' => $company->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
