<?php
//src/MyBudget/EntryBundle/Entity/Entry.php
namespace MyBudget\EntryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="entry")
 */
class Entry
{
	/** Properties **/
	/** 
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/** @ORM\ManyToOne(targetEntity="MyBudget\CategoryBundle\Entity\Category") */
	protected $category;

	/** @ORM\Column(type="boolean") */
	protected $haber;

	/** @ORM\Column(type="decimal", precision=10, scale=2) */
	protected $value;

	/** @ORM\Column(type="string", length=500) */
	protected $comment;

    /** Setters **/
    /**
     * Set haber
     *
     * @param boolean $haber
     */
    public function setHaber($haber)
    {
        $this->haber = $haber;
    }

    /**
     * Set value
     *
     * @param decimal $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Set comment
     *
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Set category
     *
     * @param MyBudget\CategoryBundle\Entity\Category $category
     */
    public function setCategory(\MyBudget\CategoryBundle\Entity\Category $category)
    {
        $this->category = $category;
    }

    /** Getters **/
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
     * Get haber
     *
     * @return boolean 
     */
    public function getHaber()
    {
        return $this->haber;
    }

    /**
     * Get value
     *
     * @return decimal 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Get category
     *
     * @return MyBudget\CategoryBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /** 
     * magic method __toString()
     */
    public function __toString()
    {
        return $this->value;
    }
}