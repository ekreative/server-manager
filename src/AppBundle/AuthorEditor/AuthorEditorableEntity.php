<?php

namespace AppBundle\AuthorEditor;

use AppBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

trait AuthorEditorableEntity
{

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $author;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $editor;

    /**
     * @param User $author
     * @return self
     */
    public function setAuthor(User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param User $editor
     * @return self
     */
    public function setEditor(User $editor = null)
    {

        $this->editor = $editor;

        return $this;
    }

    /**
     * @return User
     */
    public function getEditor()
    {
        return $this->editor;
    }

}
