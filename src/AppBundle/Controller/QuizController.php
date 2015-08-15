<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Quiz;
use AppBundle\Entity\QuizApproach;
use AppBundle\Entity\QuestionApproach;
use AppBundle\Form\QuizType;
use AppBundle\Form\QuizApproachType;

/**
 * Quiz controller.
 *
 * @Route("/")
 */
class QuizController extends Controller
{
  /**
   * Lists all Quiz entities.
   *
   * @Route("/", name="main")
   * @Method("GET")
   * @Template()
   */
  public function mainAction()
  {
      return [];
  }

  /**
   * Lists all Quiz entities.
   *
   * @Route("/rank", name="quiz_approach_rank")
   * @Method("GET")
   * @Template()
   */
  public function rankAction()
  {
      $em = $this->getDoctrine()->getManager();

      $entities = $em->getRepository('AppBundle:QuizApproach')->findAll();

      return array(
          'entities' => $entities,
      );
  }

    /**
     * Lists all Quiz entities.
     *
     * @Route("/lista", name="quiz")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Quiz')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a form to create a Quiz entity.
     *
     * @param Quiz $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Quiz $entity)
    {
        $form = $this->createForm(new QuizType(), $entity, array(
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to create a new Quiz entity.
     *
     * @Route("/dodaj", name="quiz_new")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('AppBundle:Quiz')->createQueryBuilder('q');
        $qb->select('COUNT(q)');
        $count = $qb->getQuery()->getSingleScalarResult();

        $entity = new Quiz();
        $entity->setTitle('Quiz - ' . $count);
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirectToRoute('quiz_show', array('link' => $entity->getLink()));

        }
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Quiz entity.
     *
     * @Route("/approach/new/{id}", name="quiz_approach")
     * @Template()
     */
    public function quizAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $quiz = $em->getRepository('AppBundle:Quiz')->find($id);

        if (!$quiz)
          throw $this->createNotFoundException("Quiz nie istnieje");

        $entity = new QuizApproach();
        $entity->setQuiz($quiz);

        foreach ($quiz->getQuestions() as $question) {
          $questionApproach = new QuestionApproach();
          $questionApproach->setQuestion($question);
          $entity->addQuestionsApproach($questionApproach);
        }
        $form = $this->createForm(new QuizApproachType(), $entity, []);

        $form->handleRequest($request);
        if ($form->isValid()) {

          if ($form->get('end')->isClicked()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('approach_show', array('id' => $entity->getId())));
            }
        }
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     *
     * @Route("/approach/{id}", name="approach_show")
     * @Method("GET")
     * @Template()
     */
    public function approachAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:QuizApproach')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Brak encji.');
        }

        return array(
            'entity'      => $entity
        );
    }

    /**
     * Finds and displays a Quiz entity.
     *
     * @Route("/quiz/{link}", name="quiz_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($link)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Quiz')->findOneByLink($link);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Quiz entity.');
        }

        //$deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity
        );
    }

    /**
     * Displays a form to edit an existing Quiz entity.
     *
     * @Route("/{id}/edit", name="quiz_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Quiz')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Quiz entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Quiz entity.
    *
    * @param Quiz $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Quiz $entity)
    {
        $form = $this->createForm(new QuizType(), $entity, array(
            'action' => $this->generateUrl('quiz_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));


        return $form;
    }
    /**
     * Edits an existing Quiz entity.
     *
     * @Route("/{id}", name="quiz_update")
     * @Method("PUT")
     * @Template("AppBundle:Quiz:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Quiz')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Quiz entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('quiz_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Quiz entity.
     *
     * @Route("/{id}", name="quiz_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Quiz')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Quiz entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('quiz'));
    }

    /**
     * Creates a form to delete a Quiz entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('quiz_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
