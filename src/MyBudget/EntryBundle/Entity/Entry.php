<?php
//src/MyBudget/EntryBundle/Entity/Entry.php
namespace MyBudget\EntryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="MyBudget\EntryBundle\Entity\EntryRepository")
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

	/** 
     * @ORM\ManyToOne(targetEntity="MyBudget\CategoryBundle\Entity\Category", inversedBy="entries")
     * @Assert\NotBlank(message = "Debes elegir una categoría.")
    */
	protected $category;

    /** 
     * @ORM\Column(name="date_entry", type="date")
     * @Assert\NotBlank(message = "Debes ingresar una fecha.")
    */
    protected $date_entry;

	/** @ORM\Column(type="boolean") */
	protected $haber;

	/** 
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\NotBlank(message = "Debes ingresar un valor.")
    */
	protected $value;

	/** @ORM\Column(type="string", length=500, nullable=true) */
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

    public function setDateEntry($date)
    {
        $this->date_entry = $date;
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

    public function getDateEntry()
    {
        return $this->date_entry;
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

    /*
     * toArray() method
     * Converts the object into an array
     */
    public function toArray()
    {
        $array = array();

        $array['id'] = $this->id;
        $array['category'] = $this->getCategory()->toArray();
        $array['date_entry'] = $this->date_entry;
        $array['haber'] = $this->haber;
        $array['value'] = $this->value;
        $array['comment'] = $this->comment;
        $array['created_at'] = $this->created_at;
        $array['updated_at'] = $this->updated_at;

        return $array;
    }
}