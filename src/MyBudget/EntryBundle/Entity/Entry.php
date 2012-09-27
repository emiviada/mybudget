<?php
//src/MyBudget/EntryBundle/Entity/Entry.php
namespace MyBudget\EntryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

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

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created_at;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updated_at;

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

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
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

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /** 
     * magic method __toString()
     */
    public function __toString()
    {
        return $this->value;
    }
}