<?php

namespace App\Entity;

use App\Application\Sonata\UserBundle\Entity\User;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Article
 *
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @Vich\Uploadable
 */
class Article extends BaseEntity
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
     * @var bool
     *
     * @ORM\Column(name="is_Recumended", type="boolean", nullable=true)
     */
    private $isRecumended;

    /**
     * @var int
     *
     * @ORM\Column(name="point", type="integer", nullable=true)
     */
    private $point;

    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User", inversedBy="article")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     *
     * @ORM\ManyToOne(targetEntity="ArticleType", inversedBy="article")
     * @ORM\JoinColumn(name="article_type_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $articleTypeId;

    /**
     * @var string
     *
     * @ORM\Column(name="tag", type="text", nullable=true)
     */
    private $tag;

    /**
     *
     * @Vich\UploadableField(mapping="article_image", fileNameProperty="imageName")
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
     * @ORM\OneToMany(targetEntity="ProfileKeyArticles", mappedBy="articleId")
     */
    private $profileKeyArticles;

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
     * @return Article
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
     * @return Article
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
     * @return Article
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
     * Set isRecumended
     *
     * @param boolean $isRecumended
     *
     * @return Article
     */
    public function setIsRecumended($isRecumended)
    {
        $this->isRecumended = $isRecumended;

        return $this;
    }

    /**
     * Get isRecumended
     *
     * @return bool
     */
    public function getIsRecumended()
    {
        return $this->isRecumended;
    }

    /**
     * Set point
     *
     * @param integer $point
     *
     * @return Article
     */
    public function setPoint($point)
    {
        $this->point = $point;

        return $this;
    }

    /**
     * Get point
     *
     * @return int
     */
    public function getPoint()
    {
        return $this->point;
    }


    /**
     * Set tag
     *
     * @param string $tag
     *
     * @return Article
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return Article
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set articleTypeId
     *
     * @param ArticleType $articleTypeId
     *
     * @return Article
     */
    public function setArticleTypeId(ArticleType $articleTypeId = null)
    {
        $this->articleTypeId = $articleTypeId;

        return $this;
    }

    /**
     * Get articleTypeId
     *
     * @return ArticleType
     */
    public function getArticleTypeId()
    {
        return $this->articleTypeId;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return Article
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
     * @return Article
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
            return '/images/article/'.$this->imageName;
        } else {
            return '/icon/default_image.png';
        }
    }








    /**
     * Set nameAr
     *
     * @param string $nameAr
     *
     * @return Article
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
     * @return Article
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
     * @return Article
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

    /**
     * Add profileKeyArticle
     *
     * @param ProfileKeyArticles $profileKeyArticle
     *
     * @return Article
     */
    public function addProfileKeyArticle(ProfileKeyArticles $profileKeyArticle)
    {
        $this->profileKeyArticles[] = $profileKeyArticle;

        return $this;
    }

    /**
     * Remove profileKeyArticle
     *
     * @param ProfileKeyArticles $profileKeyArticle
     */
    public function removeProfileKeyArticle(ProfileKeyArticles $profileKeyArticle)
    {
        $this->profileKeyArticles->removeElement($profileKeyArticle);
    }

    /**
     * Get profileKeyArticles
     *
     * @return Collection
     */
    public function getProfileKeyArticles()
    {
        return $this->profileKeyArticles;
    }
}
