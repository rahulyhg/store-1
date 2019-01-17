<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StoreAnalytics
 *
 * @ORM\Table(name="store_analytics")
 * @ORM\Entity
 */
class StoreAnalytics
{
    /**
     * @var int
     *
     * @ORM\Column(name="analytics_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $analyticsId;

    /**
     * @var string
     *
     * @ORM\Column(name="analytics_name", type="text", length=65535, nullable=false)
     */
    private $analyticsName;

    /**
     * @var string
     *
     * @ORM\Column(name="analytics_code", type="text", length=65535, nullable=false)
     */
    private $analyticsCode;

    public function getAnalyticsId(): ?int
    {
        return $this->analyticsId;
    }

    public function getAnalyticsName(): ?string
    {
        return $this->analyticsName;
    }

    public function setAnalyticsName(string $analyticsName): self
    {
        $this->analyticsName = $analyticsName;

        return $this;
    }

    public function getAnalyticsCode(): ?string
    {
        return $this->analyticsCode;
    }

    public function setAnalyticsCode(string $analyticsCode): self
    {
        $this->analyticsCode = $analyticsCode;

        return $this;
    }


}
