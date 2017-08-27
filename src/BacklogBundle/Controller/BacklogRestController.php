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

    public function getItemsAction(Request $request)
    {
        /** @var ItemRepository $repository */
        $repository = $this->get('item_repository');

        // we dont need pagination wrapper in rest api
        $items = $repository->findAll();

        $view = $this->view($items, 200);

        return $this->handleView($view);
    }

    public function getItemAction($id, Request $request)
    {
        /** @var ItemRepository $repository */
        $repository = $this->get('item_repository');

        $item = $repository->findOneById($id);

        $view = $this->view($item, 200);

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

            return $this->routeRedirectView('get_items');
        }

        $view = $this->view($form);
        return $this->handleView($view);
    }

    public function putItemAction($id, Request $request)
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

        $form = $this->createForm(UpdateItemType::class, $item, ['sprint_query' => $sprintQuery, 'allow_extra_fields' => true]);

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