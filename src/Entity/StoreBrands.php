<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StoreBrands
 *
 * @ORM\Table(name="store_brands")
 * @ORM\Entity
 */
class StoreBrands
{
    /**
     * @var int
     *
     * @ORM\Column(name="brand_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $brandId;

    /**
     * @var string
     *
     * @ORM\Column(name="brand_name", type="text", length=65535, nullable=false)
     */
    private $brandName;

    /**
     * @var string
     *
     * @ORM\Column(name="brand_description", type="text", length=65535, nullable=false)
     */
    private $brandDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="brand_image", type="text", length=65535, nullable=false)
     */
    private $brandImage;

    /**
     * @var string
     *
     * @ORM\Column(name="brand_link", type="text", length=65535, nullable=false)
     */
    private $brandLink;

    public function getBrandId(): ?int
    {
        return $this->brandId;
    }

    public function getBrandName(): ?string
    {
        return $this->brandName;
    }

    public function setBrandName(string $brandName): self
    {
        $this->brandName = $brandName;

        return $this;
    }

    public function getBrandDescription(): ?string
    {
        return $this->brandDescription;
    }

    public function setBrandDescription(string $brandDescription): self
    {
        $this->brandDescription = $brandDescription;

        return $this;
    }

    public function getBrandImage(): ?string
    {
        return $this->brandImage;
    }

    public function setBrandImage(string $brandImage): self
    {
        $this->brandImage = $brandImage;

        return $this;
    }

    public function getBrandLink(): ?string
    {
        return $this->brandLink;
    }

    public function setBrandLink(string $brandLink): self
    {
        $this->brandLink = $brandLink;

        return $this;
    }


}
