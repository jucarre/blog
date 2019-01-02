<?php

namespace App\Model;

class Post
{
    private $id;
    private $title;
    private $chapo;
    private $content;
    private $idUser;
    private $creationDate;

    /**
     * Get the value of id.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id.
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of title.
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title.
     *
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of chapo.
     */
    public function getChapo()
    {
        return $this->chapo;
    }

    /**
     * Set the value of chapo.
     *
     * @return self
     */
    public function setChapo($chapo)
    {
        $this->chapo = $chapo;

        return $this;
    }

    /**
     * Get the value of content.
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the value of content.
     *
     * @return self
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the value of idUser.
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set the value of idUser.
     *
     * @return self
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get the value of creationDate.
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set the value of creationDate.
     *
     * @return self
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }
}
