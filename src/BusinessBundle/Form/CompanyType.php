<?php

namespace BusinessBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
// use Symfony\Component\Form\FormEvent;
// use Symfony\Component\Form\FormEvents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class CompanyType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name');

        $this->companyId = $builder->getData()->getId();

        $builder->add('director', EntityType::class, [
            'class' => 'BusinessBundle:Person',
            'choice_label' => 'name',
            'label' => 'Director',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('p')
                ->innerJoin('p.affiliate', 'a')
                ->innerJoin('a.company', 'c')
                ->where('a.company = :companyId')
                ->andWhere('c.accountant IS NULL OR c.accountant IS NOT NULL AND p.id != c.accountant')
                ->setParameter('companyId', $this->companyId)
                ->orderBy('p.name', 'ASC');
            }
        ]);

        $builder->add('accountant', EntityType::class, [
            'class' => 'BusinessBundle:Person',
            'choice_label' => 'name',
            'label' => 'Chief Accountant',
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
