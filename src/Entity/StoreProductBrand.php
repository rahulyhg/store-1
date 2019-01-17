<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StoreProductBrand
 *
 * @ORM\Table(name="store_product_brand")
 * @ORM\Entity
 */
class StoreProductBrand
{
    /**
     * @var int
     *
     * @ORM\Column(name="product_brand_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $productBrandId;

    /**
     * @var int
     *
     * @ORM\Column(name="product_id", type="integer", nullable=false)
     */
    private $productId;

    /**
     * @var int
     *
     * @ORM\Column(name="brand_id", type="integer", nullable=false)
     */
    private $brandId;

    public function getProductBrandId(): ?int
    {
        return $this->productBrandId;
    }

    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): self
    {
        $this->productId = $productId;

        return $this;
    }

    public function getBrandId(): ?int
    {
        return $this->brandId;
    }

    public function setBrandId(int $brandId): self
    {
        $this->brandId = $brandId;

        return $this;
    }


}
