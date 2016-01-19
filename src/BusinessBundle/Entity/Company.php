<?php

namespace BusinessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
    private $directorId;

    /**
     * @ORM\ManyToOne(targetEntity="Person")
     * @ORM\JoinColumn(name="id_accountant", referencedColumnName="id", nullable=true)
     */
    private $accountantId;

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
     * Set directorId
     *
     * @param \BusinessBundle\Entity\Person $directorId
     *
     * @return Company
     */
    public function setDirectorId(\BusinessBundle\Entity\Person $directorId = null)
    {
        $this->directorId = $directorId;

        return $this;
    }

    /**
     * Get directorId
     *
     * @return \BusinessBundle\Entity\Person
     */
    public function getDirectorId()
    {
        return $this->directorId;
    }

    /**
     * Set accountantId
     *
     * @param \BusinessBundle\Entity\Person $accountantId
     *
     * @return Company
     */
    public function setAccountantId(\BusinessBundle\Entity\Person $accountantId = null)
    {
        $this->accountantId = $accountantId;

        return $this;
    }

    /**
     * Get accountantId
     *
     * @return \BusinessBundle\Entity\Person
     */
    public function getAccountantId()
    {
        return $this->accountantId;
    }
}
