<?php
// src/Sdz/BlogBundle/Entity/Categorie.php
 
namespace Sdz\BlogBundle\Entity;
 
use Doctrine\ORM\Mapping as ORM;
 
/**
 * Sdz\BlogBundle\Entity\Categorie
 *
 * @ORM\Table(name="categorie")
 * @ORM\Entity(repositoryClass="Sdz\BlogBundle\Entity\CategorieRepository")
 */
class Categorie
{
    /**
     * @ORM\ManyToMany(targetEntity="Sdz\BlogBundle\Entity\Categorie", cascade={"persist"})
     */
    private $categories;

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $nom
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;


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
     * Set nom
     *
     * @param string $nom
     * @return Categorie
     */
    public function setNom($nom)
    {
      $this->nom = $nom;
      return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
      return $this->nom;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add categories
     *
     * @param \Sdz\BlogBundle\Entity\Categorie $categories
     * @return Categorie
     */
    public function addCategory(\Sdz\BlogBundle\Entity\Categorie $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Sdz\BlogBundle\Entity\Categorie $categories
     */
    public function removeCategory(\Sdz\BlogBundle\Entity\Categorie $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }
}
