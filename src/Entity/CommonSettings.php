<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommonSettings
 *
 * @ORM\Table(name="common_settings")
 * @ORM\Entity
 */
class CommonSettings
{
    /**
     * @var int
     *
     * @ORM\Column(name="setting_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $settingId;

    /**
     * @var string
     *
     * @ORM\Column(name="setting_name", type="text", length=65535, nullable=false)
     */
    private $settingName;

    /**
     * @var string
     *
     * @ORM\Column(name="setting_data", type="text", length=65535, nullable=false)
     */
    private $settingData;

    public function getSettingId(): ?int
    {
        return $this->settingId;
    }

    public function getSettingName(): ?string
    {
        return $this->settingName;
    }

    public function setSettingName(string $settingName): self
    {
        $this->settingName = $settingName;

        return $this;
    }

    public function getSettingData(): ?string
    {
        return $this->settingData;
    }

    public function setSettingData(string $settingData): self
    {
        $this->settingData = $settingData;

        return $this;
    }


}
