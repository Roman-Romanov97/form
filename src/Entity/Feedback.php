<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Feedback
 *
 * @ORM\Table(name="feedback")
 * @ORM\Entity
 */
class Feedback
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="feedback_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_contact", type="datetime", nullable=false)
     */
    private $dateContact;

    /**
     * @var string
     *
     * @ORM\Column(name="text_feedback", type="text", nullable=false)
     */
    private $textFeedback;

    /**
     * @var User
     * @Assert\Type(type="App\Entity\User")
     * @Assert\Valid()
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="feedback_user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var string
     * @ORM\Column(name="email", type="text", nullable=false)
     */
    private $email;

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function __construct()
    {
        $this->dateContact = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateContact(): ?\DateTimeInterface
    {
        return $this->dateContact;
    }

    public function setDateContact(\DateTimeInterface $dateContact): self
    {
        $this->dateContact = $dateContact;

        return $this;
    }

    public function getTextFeedback(): ?string
    {
        return $this->textFeedback;
    }

    public function setTextFeedback(string $textFeedback): self
    {
        $this->textFeedback = $textFeedback;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->id;
    }

}
