<?php

namespace BacklogBundle\Controller;

use BacklogBundle\Entity\Sprint;
use Doctrine\Common\Persistence\ObjectRepository;
use PHPRum\Commands\Backlog\CreateSrpint;
use PHPRum\Commands\Backlog\StartSprint;
use PHPRum\DomainModel\Backlog\SprintDuration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class SprintController extends Controller
{
    /**
     * @Route("/sprint/", name="sprint_index")
     * @Method({"GET"})
     */
    public function listSprintsAction()
    {
        $repository = $this->getRepository();

        return $this->render(
            'sprint/index.html.twig',
            [
                'items' => $repository->findByCreator($this->getUser()),
            ]
            );
    }

    /**
     * @Route("/sprint/{id}", name="sprint_show")
     * @Method({"GET"})
     */
    public function showSprintAction($id)
    {
        $repository = $this->getRepository();
        /** @var Sprint $sprint */
        $sprint = $repository->find($id);

        return $this->render(
            'sprint/sprint_item_list.html.twig',
            [
                'items' => $sprint->getItems(),
                'points_sum' => $sprint->getTotalPoints(),
                'sprint' => $sprint,
            ]
        );
    }

    /**
     * @Route("/sprint/new", name="sprint_new")
     * @Method({"POST", "GET"})
     */
    public function addSprintAction(Request $request)
    {
        $createSprintCommand = new CreateSrpint($this->getDoctrine()->getManager());

        $form = $this->createFormBuilder($createSprintCommand)
            ->add('duration', ChoiceType::class, [
                'choices' => [
                    'One week' => SprintDuration::ONE_WEEK,
                    'Two weeks' => SprintDuration::TWO_WEEKS,
                    'Three weeks' => SprintDuration::THREE_WEEKS,
                    'Four weeks' => SprintDuration::FOUR_WEEKS,
                ],
            ])
            ->add('Save', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var CreateSrpint $command */
            $command = $form->getData();
            $command->setUser($this->getUser());
            $command->execute();

            $this->addFlash('notice', 'New sprint added');

            return $this->redirectToRoute(BacklogController::LIST_BACKLOG_ITEMS);
        }

        return $this->render('sprint/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/sprint/{id}/start", name="sprint_start")
     * @Method({"GET"})
     */
    public function startSprintAction($id)
    {
        $repository = $this->getRepository();
        /** @var Sprint $sprint */
        $sprint = $repository->find($id);

        $command = new StartSprint($this->getDoctrine()->getManager(), $sprint);

        $command->execute();

        return $this->redirectToRoute('sprint_show', ['id' => $id]);
    }

    /**
     * @Route("/sprint/{id}/end", name="sprint_end")
     * @Method({"GET"})
     */
    public function endSprintAction($id)
    {
        $repository = $this->getRepository();
        /** @var Sprint $sprint */
        $sprint = $repository->find($id);

        $sprint->end();
        $objectManager = $this->getDoctrine()->getManager();
        $objectManager->persist($sprint);
        $objectManager->flush();

        return $this->redirectToRoute('sprint_show', ['id' => $id]);
    }

    /**
     * @return ObjectRepository
     */
    protected function getRepository(): ObjectRepository
    {
        return $this->getDoctrine()->getManager()->getRepository(Sprint::class);
    }
}
