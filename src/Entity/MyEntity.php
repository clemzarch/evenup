<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity(repositoryClass="App\Repository\MyEntityRepository")
 */
class MyEntity
{
	/**
	* @const path to cover directory
    */
    const COVER_DIRECTORY = '/uploads/cover/';

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $cover;

    /**
     * @var File
     *
     * @Assert\NotBlank(message="S'il vous plait, ajoutez une image de couverture")
     * @Assert\File(mimeTypes={ "image/jpeg","image/png"  })
     */
    public $file;
	
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_enabled;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nb_like;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Category", inversedBy="articles")
	 * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
	 */
	private $category;
	
	/**
     * @ORM\OneToOne(targetEntity="MyEntity")
     * @ORM\JoinColumn(name="article_id", referencedColumnName="id")
     */
    private $article_parent;
	
	/**
	* @ORM\ManyToMany(targetEntity="Tag", inversedBy="articles", cascade={"persist"})
	* @ORM\JoinTable(name="article_tags")
	*/
	private $tags;
	
	/**
     * @return string
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * @param string $cover
     *
     * @return Article
     */
    public function setCover($cover)
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param File $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }
    
    /**
     * On part de notre class et on remonte jusqu'au dossier web
     * Chemin physique sur le serveur du dossier d'upload
     *
     * @return string
     */
    public function getCoverUploadDirectory()
    {
        return __DIR__ . "/../../public" . self::COVER_DIRECTORY;
    }

    /**
     * Chemin physique de l'image sur le serveur  
     * 
     * @return string
     */
    public function getCoverAbsolutePath()
    {
        return $this->getCoverUploadDirectory() . $this->getCover();
    }

    /**
     * Chemin de l'image via l'URL, servira dans pour l'affichage dans les templates twig
     *
     * @return string
     */
    public function getCoverWebPath()
    {
        return self::COVER_DIRECTORY . $this->getCover();
    }
	
	public function __construct()
	{
		$this->tags = new ArrayCollection();
	}
	
	
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getIsEnabled(): ?bool
    {
        return $this->is_enabled;
    }

    public function setIsEnabled(?bool $is_enabled): self
    {
        $this->is_enabled = $is_enabled;

        return $this;
    }

    public function getNbLike(): ?string
    {
        return $this->nb_like;
    }

    public function setNbLike(?string $nb_like): self
    {
        $this->nb_like = $nb_like;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category): void
    {
        $this->category = $category;
    }
	
	public function getArticleParent(): ?int
    {
        return $this->article_parent;
    }

    public function ArticleParent(int $article_parent): self
    {
        $this->article_parent = $article_parent;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags): void
    {
        $this->tags = $tags;
    }

}
