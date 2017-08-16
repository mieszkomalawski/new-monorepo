<?php


namespace AppBundle\Controller;

use AppBundle\Commands\CreateItem;
use AppBundle\Repository\ItemRepository;
use Doctrine\Common\Persistence\ObjectRepository;
use Knp\Component\Pager\Paginator;
use PHPRum\DomainModel\Backlog\Item;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
        return $this->render('backlog/item_list.html.twig', ['items' => $repository->getByPage($request->get('page', 1), 2)]);
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
            $command->execute();

            return $this->redirectToRoute('list_backlog_items');
        }

        return $this->render('backlog/item_add.html.twig', ['form' => $form->createView()]);
    }
}