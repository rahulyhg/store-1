<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StoreCategory
 *
 * @ORM\Table(name="store_category")
 * @ORM\Entity
 */
class StoreCategory
{
    /**
     * @var int
     *
     * @ORM\Column(name="category_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $categoryId;

    /**
     * @var string
     *
     * @ORM\Column(name="category_name", type="text", length=65535, nullable=false)
     */
    private $categoryName;

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function getCategoryName(): ?string
    {
        return $this->categoryName;
    }

    public function setCategoryName(string $categoryName): self
    {
        $this->categoryName = $categoryName;

        return $this;
    }


}
