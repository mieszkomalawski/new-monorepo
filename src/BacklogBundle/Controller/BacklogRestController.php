<?php


namespace BacklogBundle\Controller;


use BacklogBundle\Entity\Item;
use BacklogBundle\Form\CreateItemType;
use BacklogBundle\Repository\ItemRepository;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

class BacklogRestController extends FOSRestController
{

    public function listItemsAction(Request $request)
    {
        /** @var ItemRepository $repository */
        $repository = $this->get('item_repository');

        // we dont need pagination wrapper in rest api
        $items = $repository->findAll();

        $view = $this->view($items, 200);

        return $this->handleView($view);
    }

    public function postItemAction(Request $request)
    {
        $form = $this->createForm(CreateItemType::class, null, ['user' => $this->getUser()]);
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

            return $this->routeRedirectView('list_items');
        }

        $view = $this->view($form);
        return $this->handleView($view);
    }
}