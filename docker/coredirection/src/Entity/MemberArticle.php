<?php

namespace App\Entity;

use App\Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Sonata\FormatterBundle\Validator\Constraints\Formatter;

/**
 * MemberArticle
 *
 * @ORM\Table(name="member_article")
 * @ORM\Entity(repositoryClass="App\Repository\MemberArticleRepository")
 * @ORM\HasLifecycleCallbacks
 */
class MemberArticle
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User", inversedBy="memberArticle")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="memberArticle")
     * @ORM\JoinColumn(name="article_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $article;

    /**
     * @ORM\ManyToOne(targetEntity="CorporateKey", inversedBy="memberArticle")
     * @ORM\JoinColumn(name="key_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $key;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime")
     */
    private $createdDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_date", type="datetime")
     */
    private $updatedDate;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @ORM\PrePersist
     */
    public function onCreate()
    {
        $this->createdDate = new \DateTime();
        $this->updatedDate = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function onUpdate()
    {
        $this->updatedDate = new \DateTime();
    }



    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return MemberArticle
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set updatedDate
     *
     * @param \DateTime $updatedDate
     *
     * @return MemberArticle
     */
    public function setUpdatedDate($updatedDate)
    {
        $this->updatedDate = $updatedDate;

        return $this;
    }

    /**
     * Get updatedDate
     *
     * @return \DateTime
     */
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return MemberArticle
     */
    public function setUser(User $user)
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
     * Set article
     *
     * @param Article $article
     *
     * @return MemberArticle
     */
    public function setArticle(Article $article)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return Article
     */
    public function getArticle()
    {
        return $this->article;
    }



    /**
     * Set key
     *
     * @param CorporateKey $key
     *
     * @return MemberArticle
     */
    public function setKey(CorporateKey $key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get key
     *
     * @return CorporateKey
     */
    public function getKey()
    {
        return $this->key;
    }
}
