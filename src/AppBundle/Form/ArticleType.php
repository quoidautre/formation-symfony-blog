<?php

namespace AppBundle\Form;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use AppBundle\Entity\Tag;

class ArticleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$builder->add('title')->add('content')->add('date')->add('publicate')->add('image')->add('tags');
        $builder
            ->add('title', null, ['label' => 'Saisissez un titre'])
            ->add('content')
            ->add('date', null, [
                'format' => 'dd/MM/yyyy',
                'widget' => 'single_text',
                'html5' => false])
            ->add('publicate', null, ['required' => false])
            ->add('image', ImageType::class)
            ->add('tags', EntityType::class, [
                'required' => false,
                'class' => Tag::class,
                'choice_label' => 'title',
                'expanded' => true,
                'multiple' => true,
                'query_builder' => function (EntityRepository $er) {
                     return $er->createQueryBuilder('tags')
                        ->orderBy('tags.title', 'ASC');
                },
            ])
            ->add('submit', SubmitType::class)
            ->getForm();
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Article',
            'id' => 0
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_article';
    }


}
