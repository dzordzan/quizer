<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class QuestionApproachType extends AbstractType
{
  //private $question;

  //public function __construct($question)
  //{
  //  $this->question = $question;
  //}
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        //$question = $builder->getData()->getQuestion();


        $builder->addEventListener(
              FormEvents::PRE_SET_DATA,
              array($this, 'onPreSetData')
          );
      }

      public function onPreSetData(FormEvent $event)
      {
        $builder = $event->getForm();

        $question = $event->getData()->getQuestion();

        if ($question ===  null)
          return;

        $builder
            ->add('answer', 'entity', [
              'class' => 'AppBundle\Entity\Answer',
              'label_attr' =>[
                'style' => 'display:none;'
              ],
              'expanded' => true,
              'query_builder' => function(\Doctrine\ORM\EntityRepository $er) use ($question) {
                  return $er->createQueryBuilder('a')
                            ->where('a.question = :question')
                            ->setParameter('question', $question->getId());
                  },
              ])
        ;
      }
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\QuestionApproach'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_questionapproach';
    }
}
