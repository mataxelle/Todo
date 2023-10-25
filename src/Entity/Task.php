<?php

namespace App\Entity;

use App\Entity\Traits\BlameableEntity;
use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: 'task')]
#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    use BlameableEntity;
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(message: 'Vous devez saisir un titre')]
    private ?string $title = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: 'Vous devez saisir un contenu')]
    private ?string $content = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $isDone = null;

    /**
     * Get id
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param  string $title Title
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Set content
     *
     * @param  string $content Content
     * @return self
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get isDone
     *
     * @return bool
     */
    public function isDone(): ?bool
    {
        return $this->isDone;
    }

    /**
     * Set isDone
     *
     * @param  boolean $isDone IsDone
     * @return self
     */
    public function setIsDone(bool $isDone): self
    {
        $this->isDone = $isDone;

        return $this;
    }

    public function toggle(bool $flag): self
    {
        $this->isDone = $flag;

        return $this;
    }
}
