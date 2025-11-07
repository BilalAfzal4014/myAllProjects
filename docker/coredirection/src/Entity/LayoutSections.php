<?php

namespace App\Entity;

use App\Entity\Layout;
use Doctrine\ORM\Mapping as ORM;

/**
 * LayoutSections
 *
 * @ORM\Table(name="layout_sections")
 * @ORM\Entity(repositoryClass="App\Repository\LayoutSectionsRepository")
 */
class LayoutSections extends BaseEntity
{

   /**
    * @ORM\ManyToOne(targetEntity="App\Entity\Layout", inversedBy="layoutSections")
    * @ORM\JoinColumn(name="layout_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
    */
    private $layout;

    /**
     * @ORM\ManyToOne(targetEntity="Sections", inversedBy="layoutSections")
     * @ORM\JoinColumn(name="section_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $section;

    /**
     * @ORM\Column(name="sequence", type="integer", nullable=true )
     */
    private $sequence;

    /**
     * Set layout
     *
     * @param Layout $layout
     *
     * @return LayoutSections
     */
    public function setLayout(Layout $layout)
    {
        $this->layout = $layout;

        return $this;
    }

    /**
     * Get layout
     *
     * @return Layout
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * Set section
     *
     * @param Sections $section
     *
     * @return LayoutSections
     */
    public function setSection(Sections $section)
    {
        $this->section = $section;

        return $this;
    }

    /**
     * Get section
     *
     * @return Sections
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * Set sequence
     *
     * @param integer $sequence
     *
     * @return LayoutSections
     */
    public function setSequence($sequence)
    {
        $this->sequence = $sequence;

        return $this;
    }

    /**
     * Get sequence
     *
     * @return integer
     */
    public function getSequence()
    {
        return $this->sequence;
    }
}
