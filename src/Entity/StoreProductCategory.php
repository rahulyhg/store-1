<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StoreProductCategory
 *
 * @ORM\Table(name="store_product_category")
 * @ORM\Entity
 */
class StoreProductCategory
{
    /**
     * @var int
     *
     * @ORM\Column(name="product_category_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $productCategoryId;

    /**
     * @var int
     *
     * @ORM\Column(name="product_id", type="integer", nullable=false)
     */
    private $productId;

    /**
     * @var int
     *
     * @ORM\Column(name="category_id", type="integer", nullable=false)
     */
    private $categoryId;

    /**
     * @var int
     *
     * @ORM\Column(name="subcategory_id", type="integer", nullable=false)
     */
    private $subcategoryId;

    public function getProductCategoryId(): ?int
    {
        return $this->productCategoryId;
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

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function setCategoryId(int $categoryId): self
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    public function getSubcategoryId(): ?int
    {
        return $this->subcategoryId;
    }

    public function setSubcategoryId(int $subcategoryId): self
    {
        $this->subcategoryId = $subcategoryId;

        return $this;
    }


}
