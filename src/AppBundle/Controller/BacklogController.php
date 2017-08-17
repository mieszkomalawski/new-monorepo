<?php


namespace AppBundle\Controller;

use AppBundle\Commands\CreateItem;
use AppBundle\Repository\ItemRepository;
use PHPRum\DomainModel\Backlog\Item;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class BacklogController extends Controller
{
    /**
     * @Route("/backlog/", name="list_backlog_items")
     * @Method("GET")
     */
    public function listItemsAction(Request $request)
    {
        /** @var ItemRepository $repository */
        $repository = $this->get('item_repository');
        return $this->render('backlog/item_list.html.twig',
            ['items' => $repository->getByPage($this->getUser()->getId(), $request->get('page', 1), 10)]);
    }

    /**
     * @Route("/backlog/new", name="add_backlog_item")
     * @Method({"POST", "GET"})
     */
    public function addItemaAction(Request $request)
    {
        $form = $this->createFormBuilder(new CreateItem($this->getDoctrine()->getManager()))
            ->add('name', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Save'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * add item to backlog and save it
             */
            /** @var CreateItem $command */
            $command = $form->getData();
            $command->setUser($this->getUser());
            $command->execute();

            return $this->redirectToRoute('list_backlog_items');
        }

        return $this->render('backlog/item_add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/backlog/{id}/edit", name="edit_item")
     * @Method({"POST", "GET"})
     */
    public function editItemAction($id, Request $request)
    {
        /** @var ItemRepository $repository */
        $repository = $this->get('item_repository');

        $item = $repository->findOneById($id);

        $form = $this->createFormBuilder($item)
            ->add('name', TextType::class)
            ->add('estimate', TextType::class)
            ->add('priority', TextType::class)
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'New' => Item::STATUS_NEW,
                    'In progress' => Item::STAUS_IN_PROGRESS,
                    'Done' => Item::STATUS_DONE
                ]
            ])
            ->add('save', SubmitType::class, ['label' => 'Save'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $item = $form->getData();
            $this->getDoctrine()->getManager()->persist($item);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('list_backlog_items');
        }

        return $this->render('backlog/item_add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/backlog/{id}/delete", name="delete_item")
     * @Method({"GET"})
     */
    public function deleteItemAction($id)
    {
        /** @var ItemRepository $repository */
        $repository = $this->get('item_repository');

        $item = $repository->findOneById($id);

        $this->getDoctrine()->getManager()->remove($item);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('list_backlog_items');
    }
}