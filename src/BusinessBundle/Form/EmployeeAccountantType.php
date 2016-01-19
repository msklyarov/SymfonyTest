<?php

namespace BusinessBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Doctrine\ORM\EntityRepository;

class EmployeeAccountantType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // $builder->add('accountantId', EntityType::class, array(
        //     'class' => 'BusinessBundle:Person',
        //     'choice_label' => 'name',
        //     'label' => 'Person'
        // ));

        $this->companyId = $builder->getData()->getId();

        $builder->add('accountant', EntityType::class, [
            'class' => 'BusinessBundle:Person',
            'choice_label' => 'name',
            'label' => 'Person',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('p')
                ->innerJoin('p.affiliate', 'a')
                ->innerJoin('a.company', 'c')
                ->where('a.company = :companyId')
                ->andWhere('c.director IS NULL OR c.director IS NOT NULL AND p.id != c.director')
                ->setParameter('companyId', $this->companyId)
                ->orderBy('p.name', 'ASC');
            }
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'BusinessBundle\Entity\Company'
        ]);
    }
}