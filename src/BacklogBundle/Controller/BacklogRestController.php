<?php


namespace BacklogBundle\Controller;


use BacklogBundle\Entity\Item;
use BacklogBundle\Form\CreateItemType;
use BacklogBundle\Form\UpdateItemType;
use BacklogBundle\Repository\ItemRepository;
use Doctrine\ORM\EntityRepository;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

class BacklogRestController extends FOSRestController
{
    /**
     * @var ItemRepository
     */
    private $itemRepository;

    /**
     * BacklogRestController constructor.
     * @param ItemRepository $itemRepository
     */
    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }


    public function getItemsAction(Request $request)
    {
        // we dont need pagination wrapper in rest api
        $items = $this->itemRepository->findAll();

        $view = $this->view($items, 200);

        return $this->handleView($view);
    }

    public function getItemAction($id, Request $request)
    {
        $item = $this->itemRepository->findOneById($id);

        $view = $this->view($item, 200);

        return $this->handleView($view);
    }

    public function postItemAction(Request $request)
    {
        $form = $this->createForm(
            CreateItemType::class,
            null, [
            'user' => $this->getUser(),
            'backlog' => $this->itemRepository->getFullBacklog($this->getUser()->getId())
        ]);
        /**
         * Cos nie chce ladowac bezposrednio z requestu
         */
        $form->submit($request->request->all());

        if ($form->isValid()) {
            /**
             * add item to backlog and save it
             */
            /** @var Item $item */
            $item = $form->getData();
            $this->getDoctrine()->getManager()->persist($item);
            $this->getDoctrine()->getManager()->flush();

            return $this->routeRedirectView('get_items');
        }

        $view = $this->view($form);
        return $this->handleView($view);
    }

    public function putItemAction($id, Request $request)
    {
        $item = $this->itemRepository->findOneById($id);


        $form = $this->createForm(
            UpdateItemType::class,
            $item,
            [
                'userId' => $this->getUser()->getId(),
                'allow_extra_fields' => true
            ]
        );

        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {

            $item = $form->getData();
            $this->getDoctrine()->getManager()->persist($item);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('get_item', ['id' => $id]);
        }

        $view = $this->view($form);
        return $this->handleView($view);
    }
}