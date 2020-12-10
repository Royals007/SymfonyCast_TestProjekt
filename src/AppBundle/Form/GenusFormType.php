<?php

namespace AppBundle\Form;

use AppBundle\Entity\SubFamily;
use AppBundle\Repository\SubFamilyRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenusFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')

            // By passing null: the symfony will guess the EntityType instead of it.
            // the traditional setup with out null using EntityType
            ->add('subFamily', EntityType::class, [
                'placeholder' => 'Choose a Sub Family',
                'class' => SubFamily::class,
                //to keep all my queries in one place called below repository name
                'query_builder' => function(SubFamilyRepository $repo){
                    return $repo->createAlphabeticalQueryBuilder();
                }
            ])
            ->add('speciesCount')
            ->add('funFact')
            ->add('isPublished', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ]])
            ->add('firstDiscoveredAt', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker'],
                'html5' => false,
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Genus'
        ]);

    }

    public function getBlockPrefix()
    {
        return 'app_bundle_genus_form_type';
    }
}
