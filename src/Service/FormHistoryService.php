<?php


namespace App\Service;


use App\Entity\Feedback;
use Doctrine\ORM\EntityManagerInterface;

class FormHistoryService
{

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function getHistory(string $email)
    {
        $feedback = $this->manager->getRepository(Feedback::class);
        $messages = $feedback->findBy(['email' => $email]);
        return $messages;
    }

}