<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class StarType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array(1,2,3,4,5);
        $builder         //   ->add('grade', null, array('attr' => array('min' => 0, 'max' => 5)))
             ->add('grade', ChoiceType::class, array(
                'placeholder' => 'Choisissez...',
                'choices'  => $choices
                ))
            ->add('Add', \Symfony\Component\Form\Extension\Core\Type\SubmitType::class)
            ;
    }


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Star'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_star';
    }


}
