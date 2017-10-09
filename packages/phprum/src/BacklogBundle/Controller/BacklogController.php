<?php


namespace BacklogBundle\Controller;

use BacklogBundle\BacklogBundle;
use BacklogBundle\Entity\CompoundItem;
use BacklogBundle\Entity\SubItem;
use BacklogBundle\Entity\User;
use BacklogBundle\Form\CreateItemType;
use BacklogBundle\Form\CreateSubItemType;
use BacklogBundle\Form\SearchItemType;
use BacklogBundle\Form\SelectEpicType;
use BacklogBundle\Form\TaskStatusType;
use BacklogBundle\Form\UpdateItemType;
use BacklogBundle\Form\UpdateSubItemType;
use BacklogBundle\Repository\ItemRepository;
use BacklogBundle\Repository\ItemSearchQuery;
use BacklogBundle\Service\CreatorJailer;
use BacklogBundle\Service\ItemPriority;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\{
    SubmitType, TextType
};
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class BacklogController extends Controller
{
    const LIST_BACKLOG_ITEMS = 'list_backlog_items';
    /**
     * @var ItemPriority
     */
    private $itemPriorityService;


    /**
     * BacklogController constructor.
     * @param ItemPriority $itemPriorityService
     */
    public function __construct(ItemPriority $itemPriorityService)
    {
        $this->itemPriorityService = $itemPriorityService;
    }

    /**
     * @Route("/", name="main")
     * @Method({"GET"})
     */
    public function mainAction(Request $request)
    {
        return $this->redirectToRoute(self::LIST_BACKLOG_ITEMS);
    }

    /**
     * @Route("/backlog/", name="list_backlog_items")
     * @Method({"GET", "POST"})
     */
    public function listItemsAction(Request $request)
    {
        /** @var ItemRepository $repository */
        $repository = $this->get('item_repository');

        $searchForm = $this->createForm(SearchItemType::class, new ItemSearchQuery(), ['user_id' => $this->getUser()->getId()]);

        $searchForm->handleRequest($request);

        $items = $repository->getByPage(
            $this->getUser()->getId(),
            $request->get('page', 1),
            $searchForm->getData()
        );

        return $this->render('backlog/item_list.html.twig',
            ['items' => $items, 'searchForm' => $searchForm->createView()]);
    }

    /**
     * @Route("/backlog/new", name="add_backlog_item")
     * @Method({"POST", "GET"})
     */
    public function addItemAction(Request $request)
    {
        /** @var ItemRepository $repository */
        $repository = $this->get('item_repository');

        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(CreateItemType::class, null, [
            'user' => $user,
            'backlog' => $repository->getFullBacklog($user->getId())
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * add item to backlog and save it
             */
            /** @var CompoundItem $item */
            $item = $form->getData();
            $this->getDoctrine()->getManager()->persist($item);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute(self::LIST_BACKLOG_ITEMS);
        }

        return $this->render('backlog/item_add.html.twig', ['form' => $form->createView(), 'path' => null]);
    }


    /**
     * @Route("/backlog/{id}/edit", name="edit_item")
     * @Method({"POST", "GET"})
     */
    public function editItemAction(CompoundItem $item, Request $request)
    {
        /** @var ItemRepository $repository */
        $repository = $this->get('item_repository');

        $form = $this->createForm(UpdateItemType::class, $item,
            [
                'userId' => $this->getUser()->getId(),
                'other_items' => array_filter(
                    $repository->getFullBacklog($this->getUser()->getId())->getItems(),
                    function (CompoundItem $currentItem) use ($item)  {
                        return $currentItem->getId() !== $item->getId();
                    }
                )
            ]);

        $form->handleRequest($request);

        $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
        $path = $helper->asset($item, 'imageFile');

        if ($form->isSubmitted() && $form->isValid()) {

            $item = $form->getData();
            $this->getDoctrine()->getManager()->persist($item);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute(self::LIST_BACKLOG_ITEMS);
        }

        return $this->render('backlog/item_edit.html.twig',
            ['form' => $form->createView(), 'path' => $path, 'item' => $item]);
    }

    /**
     * @Route("/backlog/{id}/add-sub-task", name="add_sub_task")
     * @Method({"POST", "GET"})
     */
    public function addSubTask(CompoundItem $parentItem, Request $request)
    {
        $form = $this->createForm(CreateSubItemType::class, null, ['parent_item' => $parentItem]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subItem = $form->getData();
            $objectManager = $this->getDoctrine()->getManager();
            $objectManager->persist($subItem);
            $objectManager->flush();

            return $this->redirectToRoute('edit_item', ['id' => $parentItem->getId()]);
        }

        return $this->render('backlog/sub_item_add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/backlog/{id}/edit-sub-task/{subItemId}", name="edit_sub_task")
     * @Method({"POST", "GET"})
     * @ParamConverter("subItem", options={"id" = "subItemId"})
     */
    public function editSubTask(CompoundItem $parentItem, SubItem $subItem, Request $request)
    {
        $form = $this->createForm(UpdateSubItemType::class, $subItem);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subItem = $form->getData();
            $objectManager = $this->getDoctrine()->getManager();
            $objectManager->persist($subItem);
            $objectManager->flush();

            return $this->redirectToRoute('edit_item', ['id' => $parentItem->getId()]);
        }

        return $this->render('backlog/sub_item_add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/backlog/{id}/delete", name="delete_item")
     * @Method({"GET"})
     */
    public function deleteItemAction(CompoundItem $item)
    {
        $this->getDoctrine()->getManager()->remove($item);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute(self::LIST_BACKLOG_ITEMS);
    }

    /**
     * @Route("/backlog/{id}/priority", name="change_priority")
     * @Method({"POST"})
     */
    public function changeItemPriority($id, Request $request)
    {
        $priority = (int)$request->request->get('priority');

        $this->itemPriorityService->changeItemPriority($this->getUser()->getId(), $id, $priority);

        return new Response('', 200);
    }
}