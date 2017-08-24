<?php


namespace BacklogBundle\Controller;

use BacklogBundle\Commands\CreateItem;
use BacklogBundle\Entity\Item;
use BacklogBundle\Entity\Sprint;
use BacklogBundle\Form\UpdateItemType;
use BacklogBundle\Repository\ItemRepository;
use BacklogBundle\SprintPropertyAccessor;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\DataMapper\PropertyPathMapper;
use Symfony\Component\Form\Extension\Core\Type\{
    ChoiceType, SubmitType, TextType
};
use Symfony\Component\Form\FormInterface;
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
        $user = $this->getUser();
        $form = $this->createFormBuilder(
            null,
            [
            'data_class' => Item::class,
            'empty_data' => function (FormInterface $form) use ($user) {
                return new Item($form->get('name')->getData(), $user);
            }
        ])
            ->add('name', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Save'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * add item to backlog and save it
             */
            /** @var Item $item */
            $item = $form->getData();
            $this->getDoctrine()->getManager()->persist($item);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('list_backlog_items');
        }

        return $this->render('backlog/item_add.html.twig', ['form' => $form->createView(), 'path' => null]);
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

        $sprintQuery = function (EntityRepository $er) {
            $queryBuilder = $er->createQueryBuilder('Sprints');
            return $queryBuilder
                ->select()
                ->where($queryBuilder->expr()->eq('Sprints.creator', '?1'))
                ->setParameter(1, $this->getUser()->getId());
        };
        \Closure::bind($sprintQuery, $this);

        $form = $this->createForm(UpdateItemType::class, $item, ['sprint_query' => $sprintQuery]);

        $form->handleRequest($request);

        $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
        $path = $helper->asset($item, 'imageFile');

        if ($form->isSubmitted() && $form->isValid()) {

            $item = $form->getData();
            $this->getDoctrine()->getManager()->persist($item);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('list_backlog_items');
        }

        return $this->render('backlog/item_add.html.twig', ['form' => $form->createView(), 'path' => $path]);
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