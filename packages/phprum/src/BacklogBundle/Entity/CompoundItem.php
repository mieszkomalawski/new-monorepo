<?php

namespace BacklogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PHPRum\DomainModel\Backlog\SubItem;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @ORM\Entity
 * @Vich\Uploadable
 */
class CompoundItem extends \PHPRum\DomainModel\Backlog\CompoundItem
{
    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="item_image", fileNameProperty="imageName")
     *
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $imageName;

    /**
     * @param ClassMetadata $metadata
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('name', new NotBlank());
        $metadata->addPropertyConstraint('name', new Length([
            'min' => 2,
        ]));
    }

    /**
     * @return string
     */
    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    /**
     * @param string $imageName
     */
    public function setImageName(string $imageName)
    {
        $this->imageName = $imageName;
    }

    /**
     * @return File
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File $imageFile
     */
    public function setImageFile(File $imageFile)
    {
        $this->imageFile = $imageFile;
    }

    protected function doCreateSubItem(string $name): SubItem
    {
        return new \BacklogBundle\Entity\SubItem($name, $this->creator, $this);
    }

    public function setBlocks(iterable $blocks)
    {
        $this->blocks = $blocks;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
