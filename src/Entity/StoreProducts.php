<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StoreProducts
 *
 * @ORM\Table(name="store_products")
 * @ORM\Entity
 */
class StoreProducts
{
    /**
     * @var int
     *
     * @ORM\Column(name="product_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $productId;

    /**
     * @var string
     *
     * @ORM\Column(name="product_name", type="text", length=65535, nullable=false)
     */
    private $productName;

    /**
     * @var string
     *
     * @ORM\Column(name="product_description", type="text", length=65535, nullable=false)
     */
    private $productDescription;

    /**
     * @var float
     *
     * @ORM\Column(name="product_price", type="float", precision=10, scale=0, nullable=false)
     */
    private $productPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="product_metatitle", type="text", length=65535, nullable=false)
     */
    private $productMetatitle;

    /**
     * @var string
     *
     * @ORM\Column(name="product_metadescription", type="text", length=65535, nullable=false)
     */
    private $productMetadescription;

    /**
     * @var string
     *
     * @ORM\Column(name="product_metakeywords", type="text", length=65535, nullable=false)
     */
    private $productMetakeywords;

    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): self
    {
        $this->productName = $productName;

        return $this;
    }

    public function getProductDescription(): ?string
    {
        return $this->productDescription;
    }

    public function setProductDescription(string $productDescription): self
    {
        $this->productDescription = $productDescription;

        return $this;
    }

    public function getProductPrice(): ?float
    {
        return $this->productPrice;
    }

    public function setProductPrice(float $productPrice): self
    {
        $this->productPrice = $productPrice;

        return $this;
    }

    public function getProductMetatitle(): ?string
    {
        return $this->productMetatitle;
    }

    public function setProductMetatitle(string $productMetatitle): self
    {
        $this->productMetatitle = $productMetatitle;

        return $this;
    }

    public function getProductMetadescription(): ?string
    {
        return $this->productMetadescription;
    }

    public function setProductMetadescription(string $productMetadescription): self
    {
        $this->productMetadescription = $productMetadescription;

        return $this;
    }

    public function getProductMetakeywords(): ?string
    {
        return $this->productMetakeywords;
    }

    public function setProductMetakeywords(string $productMetakeywords): self
    {
        $this->productMetakeywords = $productMetakeywords;

        return $this;
    }


}
