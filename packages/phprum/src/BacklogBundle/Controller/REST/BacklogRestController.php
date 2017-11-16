<?php

namespace BacklogBundle\Controller\REST;

use BacklogBundle\Entity\CompoundItem;
use BacklogBundle\Form\CreateItemType;
use BacklogBundle\Form\UpdateItemType;
use BacklogBundle\Repository\ItemRepository;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\Exclusion\ExclusionStrategyInterface;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\Metadata\PropertyMetadata;
use JMS\Serializer\Serializer;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BacklogRestController extends FOSRestController
{
    /**
     * @var ItemRepository
     */
    private $itemRepository;

    /**
     * BacklogRestController constructor.
     *
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
        $context = $this->getSerializationContext();
        $view = $this->view($items, 200)->setContext($context);

        return $this->handleView($view);
    }

    public function getItemAction($id, Request $request)
    {
        $item = $this->itemRepository->findOneById($id);

        $context = $this->getSerializationContext();
        $view = $this->view($item, 200)->setContext($context);

        return $this->handleView($view);
    }

    public function postItemAction(Request $request)
    {
        $form = $this->createForm(
            CreateItemType::class,
            null,
            [
            'user' => $this->getUser(),
            'backlog' => $this->itemRepository->getFullBacklog($this->getUser()->getId()),
        ]
        );

        $form->submit($request->request->all());

        if ($form->isValid()) {
            /**
             * add item to backlog and save it.
             */
            /** @var CompoundItem $item */
            $item = $form->getData();
            $this->getDoctrine()->getManager()->persist($item);
            $this->getDoctrine()->getManager()->flush();

            return new Response('', 201, ['Location' => $this->generateUrl('get_item', ['id' => $item->getId()])]);
        }

        $view = $this->view($form);

        $context = $this->getSerializationContext();
        return $this->handleView($view)->setContent($context);
    }

    public function putItemAction($id, Request $request)
    {
        $item = $this->itemRepository->findOneById($id);

        $form = $this->createForm(
            UpdateItemType::class,
            $item,
            [
                'userId' => $this->getUser()->getId(),
                'allow_extra_fields' => true,
            ]
        );

        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();
            $this->getDoctrine()->getManager()->persist($item);
            $this->getDoctrine()->getManager()->flush();

            $context = $this->getSerializationContext();
            return new Response('', 200, ['Location' => $this->generateUrl('get_item', ['id' => $item->getId()])]);
        }

        $view = $this->view($form);

        $context = $this->getSerializationContext();
        return $this->handleView($view)->setContent($context);
    }

    /**
     * @return ExclusionStrategyInterface
     */
    private function getExclusion()
    {
        return new class implements ExclusionStrategyInterface
        {
            private $fields = ['creator', 'cache'];

            public function shouldSkipClass(ClassMetadata $metadata, \JMS\Serializer\Context $context)
            {
                return false;
            }

            public function shouldSkipProperty(PropertyMetadata $property, \JMS\Serializer\Context $context)
            {
                if (empty($this->fields)) {
                    return false;
                }

                $name = $property->serializedName ?: $property->name;

                return in_array($name, $this->fields);
            }

        };
    }

    /**
     * @return Context
     */
    private function getSerializationContext(): Context
    {
        $context = new Context();
        $context->addExclusionStrategy($this->getExclusion());
        $context->enableMaxDepth();
        return $context;
    }

}
