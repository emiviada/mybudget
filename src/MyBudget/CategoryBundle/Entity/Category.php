<?php
//src/MyBudget/CategoryBundle/Entity/Category.php
namespace MyBudget\CategoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Entity
 * @ORM\Table(name="category")
 * use repository for handy tree functions
 * @ORM\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\NestedTreeRepository")
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

	/** 
	 * @ORM\Column(type="string", length=100)
	 * @Assert\NotBlank(message = "El nombre de la categoria es requerido.")
	 */
	protected $name;

	/** @ORM\Column(type="string", length=500, nullable=true) */
	protected $description;

	/**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    private $lft;

	/**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="level", type="integer")
     */
    private $level;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(name="root", type="integer", nullable=true)
     */
    private $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

	/** 
	 * @Gedmo\Slug(fields={"name"})
	 * @ORM\Column(type="string", length=100, unique=true)
	 */
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

	public function setParent(Category $parent = null)
    {
        $this->parent = $parent;    
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

	public function getParent()
    {
        return $this->parent;   
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