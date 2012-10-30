<?php
//src/MyBudget/BackendBundle/Entity/Target.php
namespace MyBudget\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="target")
 */
class Target
{
	/** Properties **/
	/** 
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/** 
     * @ORM\Column(name="month", type="date")
     * @Assert\NotBlank(message = "Debes ingresar el mes.")
    */
    protected $month;

	/** 
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\NotBlank(message = "Debes ingresar un valor.")
    */
	protected $amount;

    /** 
     * @ORM\Column(type="decimal", precision=5, scale=2, nullable=true)
    */
    protected $points;

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
     * Set month
     *
     * @param date $month
     */
    public function setMonth($month)
    {
        $this->month = $month;
    }

    /**
     * Set amount
     *
     * @param decimal $value
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * Set points
     *
     * @param decimal $points
     */
    public function setPoints($points)
    {
        $this->points = $points;
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
     * Get amount
     *
     * @return decimal 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Get points
     *
     * @return decimal 
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Get month
     *
     * @return date
     */
    public function getMonth()
    {
        return $this->month;
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
        return $this->month;
    }
}