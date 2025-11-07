<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * ArticleType
 *
 * @ORM\Table(name="article_type")
 * @ORM\Entity(repositoryClass="App\Repository\ArticleTypeRepository")
 * @Vich\Uploadable
 */
class ArticleType extends BaseEntity
{

    public function __toString()
    {
        return (string)$this->name;
    }


    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=50)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="Article", mappedBy="articleTypeId")
     */
    private $article;


    /**
     * @var string
     *
     * @ORM\Column(name="tags", type="text", nullable=true)
     */
    private $tags;

    /**
     *
     * @Vich\UploadableField(mapping="article_type", fileNameProperty="imageName")
     *
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(name="image_name" ,type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $imageName;

    /**
     *
     * @ORM\Column(name="name_ar", type="text", nullable=true)
     */
    private $nameAr;


    /**
     * @var string
     *
     * @ORM\Column(name="description_ar", type="text", nullable=true)
     */
    private $descriptionAr;


    /**
     * @var string
     *
     * @ORM\Column(name="tags_ar", type="text", nullable=true)
     */
    private $tagsAr;

    /**
     * Set code
     *
     * @param string $code
     *
     * @return ArticleType
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return ArticleType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return ArticleType
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->article = new ArrayCollection();

    }

    /**
     * Add article
     *
     * @param Article $article
     *
     * @return ArticleType
     */
    public function addArticle(Article $article)
    {
        $this->article[] = $article;

        return $this;
    }

    /**
     * Remove article
     *
     * @param Article $article
     */
    public function removeArticle(Article $article)
    {
        $this->article->removeElement($article);
    }

    /**
     * Get article
     *
     * @return Collection
     */
    public function getArticle()
    {
        return $this->article;
    }


    /**
     * Set tags
     *
     * @param string $tags
     *
     * @return ArticleType
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get tags
     *
     * @return string
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return ArticleType
     * @throws \Exception
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {

            $this->setUpdatedDate(new \DateTime());
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param string $imageName
     *
     * @return ArticleType
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImageName()
    {
        if ($this->imageName) {
            return '/images/article_type/'.$this->imageName;
        } else {
            return '/icon/default_image.png';
        }
    }

    /**
     * Set nameAr
     *
     * @param string $nameAr
     *
     * @return ArticleType
     */
    public function setNameAr($nameAr)
    {
        $this->nameAr = $nameAr;

        return $this;
    }

    /**
     * Get nameAr
     *
     * @return string
     */
    public function getNameAr()
    {
        return $this->nameAr;
    }

    /**
     * Set descriptionAr
     *
     * @param string $descriptionAr
     *
     * @return ArticleType
     */
    public function setDescriptionAr($descriptionAr)
    {
        $this->descriptionAr = $descriptionAr;

        return $this;
    }

    /**
     * Get descriptionAr
     *
     * @return string
     */
    public function getDescriptionAr()
    {
        return $this->descriptionAr;
    }

    /**
     * Set tagsAr
     *
     * @param string $tagsAr
     *
     * @return ArticleType
     */
    public function setTagsAr($tagsAr)
    {
        $this->tagsAr = $tagsAr;

        return $this;
    }

    /**
     * Get tagsAr
     *
     * @return string
     */
    public function getTagsAr()
    {
        return $this->tagsAr;
    }
}
