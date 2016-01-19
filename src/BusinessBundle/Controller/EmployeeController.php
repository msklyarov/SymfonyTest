<?php

namespace BusinessBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use BusinessBundle\Entity\Company;
use BusinessBundle\Form\EmployeeType;

use BusinessBundle\Form\EmployeeAccountantType;
use BusinessBundle\Form\EmployeeDirectorType;

/**
 * Employee controller.
 *
 * @Route("/employee")
 */
class EmployeeController extends Controller
{
    /**
     * Lists all Employee entities.
     *
     * @Route("/", name="employee_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $companies = $em->getRepository('BusinessBundle:Company')->findAll();

        return $this->render('BusinessBundle:employee:index.html.twig', array(
            'companies' => $companies,
        ));
    }

    /**
     * Edits company director position
     *
     * @Route("/{id}/editDirector", name="employee_editDirector")
     * @Method({"GET", "POST"})
     */
    public function editDirectorAction(Request $request, Company $company)
    {
        $deleteForm = $this->createDeleteDirectorForm($company);
        $editForm = $this->createForm('BusinessBundle\Form\EmployeeDirectorType', $company);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($company);
            $em->flush();

            return $this->redirectToRoute('employee_index');
        }

        return $this->render('BusinessBundle:employee:editDirector.html.twig', [
            'company' => $company,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Edits company accountant position
     *
     * @Route("/{id}/editAccountant", name="employee_editAccountant")
     * @Method({"GET", "POST"})
     */
    public function editAccountantAction(Request $request, Company $company)
    {
        $deleteForm = $this->createDeleteAccountantForm($company);
        $editForm = $this->createForm('BusinessBundle\Form\EmployeeAccountantType', $company);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($company);
            $em->flush();

            return $this->redirectToRoute('employee_index');
        }

        return $this->render('BusinessBundle:employee:editAccountant.html.twig', [
            'company' => $company,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }


    /**
     * Deletes the director position.
     *
     * @Route("/{id}/deleteDirector", name="employee_deleteDirector")
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

        return $this->redirectToRoute('employee_index');
    }

    /**
     * Deletes the chief accountant position.
     *
     * @Route("/{id}/deleteAccountant", name="employee_deleteAccountant")
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

        return $this->redirectToRoute('employee_index');
    }


    /**
     * Creates a new Employee entity.
     *
     * @Route("/new", name="employee_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $employee = new Employee();
        $form = $this->createForm('BusinessBundle\Form\EmployeeType', $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($employee);
            $em->flush();

            return $this->redirectToRoute('employee_show', array('id' => $employee->getId()));
        }

        return $this->render('BusinessBundle:employee:new.html.twig', array(
            'employee' => $employee,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Employee entity.
     *
     * @Route("/{id}", name="employee_show")
     * @Method("GET")
     */
    public function showAction(Employee $employee)
    {
        $deleteForm = $this->createDeleteForm($employee);

        return $this->render('BusinessBundle:employee:show.html.twig', array(
            'employee' => $employee,
            'delete_form' => $deleteForm->createView(),
        ));
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
            ->setAction($this->generateUrl('employee_deleteDirector', array('id' => $company->getId())))
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
            ->setAction($this->generateUrl('employee_deleteAccountant', array('id' => $company->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
