<?php

namespace App\Entity;

use App\Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * UserDocuments
 *
 * @ORM\Table(name="user_documents")
 * @ORM\Entity(repositoryClass="App\Repository\UserDocumentsRepository")
 */
class UserDocuments extends BaseEntity
{


    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User", inversedBy="userDocument")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="documentName", type="string", length=255)
     */
    private $documentName;


    /**
     * @ORM\Column(name="is_verified", type="boolean" , options={"default":0})
     */
    private $isVerified = false;

    /**
     * Set documentName
     *
     * @param string $documentName
     *
     * @return UserDocuments
     */
    public function setDocumentName($documentName)
    {
        $this->documentName = $documentName;

        return $this;
    }

    /**
     * Get documentName
     *
     * @return string
     */
    public function getDocumentName()
    {
        return $this->documentName;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return UserDocuments
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
     * Set isVerified
     *
     * @param boolean $isVerified
     *
     * @return UserDocuments
     */
    public function setIsVerified($isVerified)
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * Get isVerified
     *
     * @return boolean
     */
    public function getIsVerified()
    {
        return $this->isVerified;
    }
}
