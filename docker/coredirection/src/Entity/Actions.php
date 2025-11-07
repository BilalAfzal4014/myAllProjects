<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Actions
 *
 * @ORM\Table(name="actions")
 * @ORM\Entity(repositoryClass="App\Repository\ActionsRepository")
 * @Vich\Uploadable
 */
class Actions extends BaseEntity
{


    public function __construct()
    {
        $this->actions = new ArrayCollection();
        $this->memberAction = new ArrayCollection();
    }

    public function __toString()
    {
        return (string)$this->name;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=25)
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
     * @var int
     *
     * @ORM\Column(name="point", type="integer", nullable=true)
     */
    private $point;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Actions", mappedBy="action")
     */
    private $actions;
    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Actions", inversedBy="actions")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $action;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MemberAction", mappedBy="action")
     */
    private $memberAction;

    /**
     *
     * @Vich\UploadableField(mapping="action_image", fileNameProperty="imageName")
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
     * Set code
     *
     * @param string $code
     *
     * @return Actions
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
     * @return Actions
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
     * @return Actions
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
     * Set imageUrl
     *
     * @param string $imageUrl
     *
     * @return Actions
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * Get imageUrl
     *
     * @return string
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * Set point
     *
     * @param integer $point
     *
     * @return Actions
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
     * Set parent
     *
     * @param string $action
     *
     * @return Actions
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get parent
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Add action
     *
     * @param Actions $action
     *
     * @return Actions
     */
    public function addAction(Actions $action)
    {
        $this->actions[] = $action;

        return $this;
    }

    /**
     * Remove action
     *
     * @param Actions $action
     */
    public function removeAction(Actions $action)
    {
        $this->actions->removeElement($action);
    }

    /**
     * Get actions
     *
     * @return Collection
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * Add memberAction
     *
     * @param \App\Entity\MemberAction $memberAction
     *
     * @return Actions
     */
    public function addMemberAction(\App\Entity\MemberAction $memberAction)
    {
        $this->memberAction[] = $memberAction;

        return $this;
    }

    /**
     * Remove memberAction
     *
     * @param \App\Entity\MemberAction $memberAction
     */
    public function removeMemberAction(\App\Entity\MemberAction $memberAction)
    {
        $this->memberAction->removeElement($memberAction);
    }

    /**
     * Get memberAction
     *
     * @return Collection
     */
    public function getMemberAction()
    {
        return $this->memberAction;
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
     * @return Actions
     * @throws \Exception
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {

            $this->setUpdatedDate( new \DateTime());
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
     * @return Actions
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
            return $this->imageName;
        } else {
            return 'default_image.png';
        }
    }
}