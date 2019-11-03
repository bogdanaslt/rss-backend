<?php

namespace App\Model;

use Symfony\Component\Serializer\Annotation\Groups;

class Entry
{
    /**
     *
     * @Groups({"default"})
     */
    protected $updated;
    
    /**
     *
     * @Groups({"default"})
     */
    protected $author;

    /**
     *
     * @Groups({"default"})
     */
    protected $link;
    
    /**
     *
     * @Groups({"default"})
     */
    protected $title;
    
    /**
     *
     * @Groups({"default"})
     */
    protected $summary;
    
    public function getUpdated()
    {
        return $this->updated;
    }
    
    public function setUpdated($updated)
    {
        $this->updated = $updated;
        
        return $this;
    }
    
    public function getAuthor()
    {
        return $this->author;
    }
    
    public function setAuthor($author)
    {
        $this->author = $author;
        
        return $this;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function setLink($link)
    {
        $this->link = $link;
        
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }
    
    public function setTitle($title)
    {
        $this->title = $title;
        
        return $this;
    }

    public function getSummary()
    {
        return $this->summary;
    }
    
    public function setSummary($summary)
    {
        $this->summary = $summary;
        
        return $this;
    }
    
    public function getWords()
    {
        $clean = strip_tags($this->summary);
        
        return str_word_count(strtolower($this->title . " " . $clean), 1);
    }
}
