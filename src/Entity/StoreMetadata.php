<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StoreMetadata
 *
 * @ORM\Table(name="store_metadata")
 * @ORM\Entity
 */
class StoreMetadata
{
    /**
     * @var int
     *
     * @ORM\Column(name="meta_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $metaId;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_name", type="text", length=65535, nullable=false)
     */
    private $metaName;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_content", type="text", length=65535, nullable=false)
     */
    private $metaContent;

    public function getMetaId(): ?int
    {
        return $this->metaId;
    }

    public function getMetaName(): ?string
    {
        return $this->metaName;
    }

    public function setMetaName(string $metaName): self
    {
        $this->metaName = $metaName;

        return $this;
    }

    public function getMetaContent(): ?string
    {
        return $this->metaContent;
    }

    public function setMetaContent(string $metaContent): self
    {
        $this->metaContent = $metaContent;

        return $this;
    }


}
