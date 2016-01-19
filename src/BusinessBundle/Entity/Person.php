<?php

namespace BusinessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="BusinessRepository")
 * @ORM\Table(name="person")
 */
class Person
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="name", type="string", length=127, nullable=false)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Affiliate")
     * @ORM\JoinColumn(name="id_affiliate", referencedColumnName="id", nullable=true)
     */
    private $affiliateId;

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
     * Set affiliateId
     *
     * @param \BusinessBundle\Entity\Affiliate $affiliateId
     *
     * @return Person
     */
    public function setAffiliateId(\BusinessBundle\Entity\Affiliate $affiliateId = null)
    {
        $this->affiliateId = $affiliateId;

        return $this;
    }

    /**
     * Get affiliateId
     *
     * @return \BusinessBundle\Entity\Affiliate
     */
    public function getAffiliateId()
    {
        return $this->affiliateId;
    }
}
