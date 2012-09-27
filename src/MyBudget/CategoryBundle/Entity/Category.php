<?php
//src/MyBudget/CategoryBundle/Entity/Category.php
namespace MyBudget\CategoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="category")
 */
class Category
{
	/** Properties **/
	/** 
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/** @ORM\Column(type="string", length=100) */
	protected $name;

	/** @ORM\Column(type="string", length=500) */
	protected $description;

	/** @ORM\Column(type="string", length=100) */
	protected $slug;

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
	public function setName($name)
	{
		$this->name = $name;
	}

	public function setDescription($description)
	{
		$this->description = $description;
	}

	public function setSlug($slug)
	{
		$this->slug = $slug;
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
	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function getSlug()
	{
		return $this->slug;
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
		return $this->name;
	}
}