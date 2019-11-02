<?php

namespace App\Exception;

use Doctrine\Common\Collections\ArrayCollection;

class InvalidModelException extends \Exception
{
    
    /**
     *
     * @var ArrayCollection
     */
    private $violations;
    
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = NULL)
    {
        parent::__construct($message, $code, $previous);
        $this->violations = new ArrayCollection();
    }
    
    public function setViolations($violations): self
    {
        $this->violations = $violations;
        
        return $this;
    }
    
    public function getViolations()
    {
        return $this->violations;
    }
    
    public function addViolation($violation)
    {
        if (!$this->violations->contains($violation)) {
            $this->violations->add($violation);
        }
        
        return $this;
    }
    
    public function removeViolation($violation)
    {
        if ($this->violations->contains($violation)) {
            $this->violations->removeElement($violation);
        }
        
        return $this;
    }
    
    public function clearViolations()
    {
        $this->violations->clear();
        
        return $this;
    }
    
    public function getErrors()
    {
        $errors = [];
        foreach($this->violations as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }
        
        return $errors;
    }
    
}
