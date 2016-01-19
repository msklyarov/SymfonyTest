<?php

namespace BusinessBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use BusinessBundle\Entity\Person;
use BusinessBundle\Form\PersonType;

/**
 * Person controller.
 *
 * @Route("/person")
 */
class PersonController extends Controller
{
    /**
     * Lists all Person entities.
     *
     * @Route("/", name="person_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $persons = $em->getRepository('BusinessBundle:Person')->findAll();

        return $this->render('person/index.html.twig', array(
            'persons' => $persons,
        ));
    }

    /**
     * Creates a new Person entity.
     *
     * @Route("/new", name="person_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $person = new Person();
        $form = $this->createForm('BusinessBundle\Form\PersonType', $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();

            return $this->redirectToRoute('person_show', array('id' => $person->getId()));
        }

        return $this->render('person/new.html.twig', array(
            'person' => $person,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Person entity.
     *
     * @Route("/{id}", name="person_show")
     * @Method("GET")
     */
    public function showAction(Person $person)
    {
        $deleteForm = $this->createDeleteForm($person);

        return $this->render('person/show.html.twig', array(
            'person' => $person,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Person entity.
     *
     * @Route("/{id}/edit", name="person_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Person $person)
    {
        $deleteForm = $this->createDeleteForm($person);
        $editForm = $this->createForm('BusinessBundle\Form\PersonType', $person);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();

            return $this->redirectToRoute('person_edit', array('id' => $person->getId()));
        }

        return $this->render('person/edit.html.twig', array(
            'person' => $person,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Person entity.
     *
     * @Route("/{id}", name="person_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Person $person)
    {
        $form = $this->createDeleteForm($person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $directors = $em->getRepository('BusinessBundle:Company')
                ->findBy(['directorId' => $person->getId()]);

            foreach ($directors as $director) {
                $director->setDirectorId(null);
            }

            $accountants = $em->getRepository('BusinessBundle:Company')
                ->findBy(['accountantId' => $person->getId()]);

            foreach ($accountants as $accountant) {
                $accountant->setAccountantId(null);
            }

            $persons = $em->getRepository('BusinessBundle:Person')
                ->findBy(['id' => $person->getId()]);

            foreach ($persons as $person) {
                $person->setAffiliateId(null);
            }

            $em->flush();
        }

        return $this->redirectToRoute('person_index');
    }


            // 'query_builder' => function (EntityRepository $er) {
            //     return $er->createQueryBuilder('p')
            //     ->innerJoin('p.affiliateId', 'a')
            //     ->innerJoin('a.companyId', 'c')
            //     ->where('a.companyId = :companyId')
            //     ->andWhere('c.accountantId IS NULL OR c.accountantId IS NOT NULL AND p.id != c.accountantId')
            //     ->setParameter('companyId', $this->companyId)
            //     ->orderBy('p.name', 'ASC');
            // }


    /**
     * Creates a form to delete a Person entity.
     *
     * @param Person $person The Person entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Person $person)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('person_delete', array('id' => $person->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
