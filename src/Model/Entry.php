<?php

namespace App\Model;

use Symfony\Component\Serializer\Annotation\Groups;

class Entry
{

    /**
     *
     * @var string
     * @Groups({"default"})
     */
    protected $updated;

    /**
     *
     * @var string
     * @Groups({"default"})
     */
    protected $author;

    /**
     *
     * @var string
     * @Groups({"default"})
     */
    protected $link;

    /**
     *
     * @var string
     * @Groups({"default"})
     */
    protected $title;

    /**
     *
     * @var string
     * @Groups({"default"})
     */
    protected $summary;

    /**
     * 
     * @return DateTime|null
     */
    public function getUpdated(): ?\DateTime
    {
        return $this->updated;
    }

    /**
     * 
     * @param DateTime|null $updated
     * @return \self
     */
    public function setUpdated(?\DateTime $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * 
     * @return string|null
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * 
     * @param string|null $author
     * @return \self
     */
    public function setAuthor(?string $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * 
     * @return string|null
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * 
     * @param string|null $link
     * @return \self
     */
    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    /**
     * 
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * 
     * @param string|null $title
     * @return \self
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * 
     * @return string|null
     */
    public function getSummary(): ?string
    {
        return $this->summary;
    }

    /**
     * 
     * @param string|null $summary
     * @return \self
     */
    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

}
