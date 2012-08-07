<?php
//src/MyBudget/CategoryBundle/Entity/Category.php
namespace MyBudget\CategoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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

	/** 
	 * magic method __toString()
	 */
	public function __toString()
	{
		return $this->name;
	}
}