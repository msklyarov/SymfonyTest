<?php

namespace BusinessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="BusinessRepository")
 * @ORM\Table(name="company")
 */
class Company
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
     * @ORM\ManyToOne(targetEntity="Person")
     * @ORM\JoinColumn(name="id_director", referencedColumnName="id", nullable=true)
     */
    private $director;

    /**
     * @ORM\ManyToOne(targetEntity="Person")
     * @ORM\JoinColumn(name="id_accountant", referencedColumnName="id", nullable=true)
     */
    private $accountant;

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        if ($this->getDirector() !== null && $this->getAccountant() !== null &&
            $this->getDirector()->getId() === $this->getAccountant()->getId()) {
            $context
                ->buildViolation('You cannot set same person for director and chief accountant position.')
                ->addViolation();
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
     * @return Company
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
     * Set director
     *
     * @param \BusinessBundle\Entity\Person $director
     *
     * @return Company
     */
    public function setDirector(\BusinessBundle\Entity\Person $director = null)
    {
        $this->director = $director;

        return $this;
    }

    /**
     * Get director
     *
     * @return \BusinessBundle\Entity\Person
     */
    public function getDirector()
    {
        return $this->director;
    }

    /**
     * Set accountant
     *
     * @param \BusinessBundle\Entity\Person $accountant
     *
     * @return Company
     */
    public function setAccountant(\BusinessBundle\Entity\Person $accountant = null)
    {
        $this->accountant = $accountant;

        return $this;
    }

    /**
     * Get accountant
     *
     * @return \BusinessBundle\Entity\Person
     */
    public function getAccountant()
    {
        return $this->accountant;
    }
}
