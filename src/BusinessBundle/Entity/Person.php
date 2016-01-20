<?php

namespace BusinessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

/**
 * @ORM\Entity(repositoryClass="BusinessRepository")
 * @ORM\Table(name="person")
 * @ORM\HasLifecycleCallbacks
 */
class Person
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=127, nullable=false)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Affiliate")
     * @ORM\JoinColumn(name="id_affiliate", referencedColumnName="id", nullable=true)
     */
    private $affiliate;

    /**
     * @ORM\PreUpdate
     */
    public function onSetAffiliateToNull(LifecycleEventArgs $event)
    {
        if ($event->hasChangedField('affiliate')) {
            $oldAffiliate = $event->getOldValue('affiliate');
            $newAffiliate = $event->getNewValue('affiliate');

            if ($oldAffiliate !== null && $newAffiliate === null) { 

                if ($oldAffiliate->getCompany() !== null) {
                    if ($oldAffiliate->getCompany()->getDirector() !== null &&
                        $oldAffiliate->getCompany()->getDirector()->getId() === $this->getId()) {

                        $em = $event->getEntityManager();

                        $companies = $em->getRepository('BusinessBundle:Company')
                            ->findBy(['id' => $oldAffiliate->getCompany()->getId()]);

                        foreach ($companies as $company) {
                            $company->setDirector(null);
                        }

                        $em->flush();

                    } else if ($oldAffiliate->getCompany()->getAccountant() !== null &&
                        $oldAffiliate->getCompany()->getAccountant()->getId() === $this->getId()) {

                        $em = $event->getEntityManager();

                        $companies = $em->getRepository('BusinessBundle:Company')
                            ->findBy(['id' => $oldAffiliate->getCompany()->getId()]);

                        foreach ($companies as $company) {
                            $company->setAccountant(null);
                        }

                        $em->flush();
                    }
                }
            }
        }
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Person
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set affiliate
     *
     * @param \BusinessBundle\Entity\Affiliate $affiliate
     *
     * @return Person
     */
    public function setAffiliate(\BusinessBundle\Entity\Affiliate $affiliate = null)
    {
        // if ($this->affiliate !== null && $affiliate === null) {

        //     if ($this->affiliate->getCompany() !== null) {
        //         if ($this->affiliate->getCompany()->getDirector() !== null &&
        //             $this->affiliate->getCompany()->getDirector()->getId() === $this->getId()) {
        //             $this->affiliate->getCompany()->setDirector(null);
        //         } else if ($this->affiliate->getCompany()->getAccountant() !== null &&
        //             $this->affiliate->getCompany()->getAccountant()->getId() === $this->getId()) {
        //             $this->affiliate->getCompany()->setAccountant(null);
        //         }
        //     }
        // }

        $this->affiliate = $affiliate;

        return $this;
    }

    /**
     * Get affiliate
     *
     * @return \BusinessBundle\Entity\Affiliate
     */
    public function getAffiliate()
    {
        return $this->affiliate;
    }
}
