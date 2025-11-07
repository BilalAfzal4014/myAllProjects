<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProfileKeyArticles
 *
 * @ORM\Table(name="profile_key_articles")
 * @ORM\Entity(repositoryClass="App\Repository\ProfileKeyArticlesRepository")
 */
class ProfileKeyArticles extends BaseEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="ProfileKey", inversedBy="profileKeyArticles")
     * @ORM\JoinColumn(name="profile_key_id", referencedColumnName="id")
     */
    private $profileKeyId;

    /**
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="profileKeyArticles")
     * @ORM\JoinColumn(name="article_id", referencedColumnName="id")
     */
    private $articleId;

    /**
     * Set profileKeyId
     *
     * @param string $profileKeyId
     *
     * @return ProfileKeyArticles
     */
    public function setProfileKeyId($profileKeyId)
    {
        $this->profileKeyId = $profileKeyId;

        return $this;
    }

    /**
     * Get profileKeyId
     *
     * @return string
     */
    public function getProfileKeyId()
    {
        return $this->profileKeyId;
    }

    /**
     * Set articleId
     *
     * @param string $articleId
     *
     * @return ProfileKeyArticles
     */
    public function setArticleId($articleId)
    {
        $this->articleId = $articleId;

        return $this;
    }

    /**
     * Get articleId
     *
     * @return string
     */
    public function getArticleId()
    {
        return $this->articleId;
    }
}
