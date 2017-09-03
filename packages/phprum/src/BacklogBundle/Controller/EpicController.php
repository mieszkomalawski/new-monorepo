<?php

namespace BacklogBundle\Controller;

use BacklogBundle\Entity\Epic;
use BacklogBundle\Form\EpicType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Epic controller.
 *
 * @Route("epic")
 */
class EpicController extends Controller
{
    /**
     * Lists all epic entities.
     *
     * @Route("/", name="epic_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $epics = $em->getRepository('BacklogBundle:Epic')->findAll();

        return $this->render('epic/index.html.twig', array(
            'epics' => $epics,
        ));
    }

    /**
     * Creates a new epic entity.
     *
     * @Route("/new", name="epic_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {

        $form = $this->createForm(EpicType::class, null , ['user' => $this->getUser()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Epic $epic */
            $epic = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($epic);
            $em->flush();

            return $this->redirectToRoute('epic_show', array('id' => $epic->getId()));
        }

        return $this->render('epic/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a epic entity.
     *
     * @Route("/{id}", name="epic_show")
     * @Method("GET")
     */
    public function showAction(Epic $epic)
    {
        $deleteForm = $this->createDeleteForm($epic);

        return $this->render('epic/show.html.twig', array(
            'epic' => $epic,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing epic entity.
     *
     * @Route("/{id}/edit", name="epic_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Epic $epic)
    {
        $deleteForm = $this->createDeleteForm($epic);
        $editForm = $this->createForm('BacklogBundle\Form\EpicType', $epic, ['user' => $this->getUser()]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('epic_edit', array('id' => $epic->getId()));
        }

        return $this->render('epic/edit.html.twig', array(
            'epic' => $epic,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a epic entity.
     *
     * @Route("/{id}", name="epic_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Epic $epic)
    {
        $form = $this->createDeleteForm($epic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($epic);
            $em->flush();
        }

        return $this->redirectToRoute('epic_index');
    }

    /**
     * Creates a form to delete a epic entity.
     *
     * @param Epic $epic The epic entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Epic $epic)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('epic_delete', array('id' => $epic->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
