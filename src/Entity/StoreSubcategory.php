<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StoreSubcategory
 *
 * @ORM\Table(name="store_subcategory")
 * @ORM\Entity
 */
class StoreSubcategory
{
    /**
     * @var int
     *
     * @ORM\Column(name="subcategory_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $subcategoryId;

    /**
     * @var int
     *
     * @ORM\Column(name="category_id", type="integer", nullable=false)
     */
    private $categoryId;

    /**
     * @var string
     *
     * @ORM\Column(name="subcategory_name", type="text", length=65535, nullable=false)
     */
    private $subcategoryName;

    public function getSubcategoryId(): ?int
    {
        return $this->subcategoryId;
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

    public function getSubcategoryName(): ?string
    {
        return $this->subcategoryName;
    }

    public function setSubcategoryName(string $subcategoryName): self
    {
        $this->subcategoryName = $subcategoryName;

        return $this;
    }


}
