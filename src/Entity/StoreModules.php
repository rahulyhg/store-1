<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StoreModules
 *
 * @ORM\Table(name="store_modules")
 * @ORM\Entity
 */
class StoreModules
{
    /**
     * @var int
     *
     * @ORM\Column(name="module_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $moduleId;

    /**
     * @var string
     *
     * @ORM\Column(name="module_name", type="text", length=65535, nullable=false)
     */
    private $moduleName;

    /**
     * @var string
     *
     * @ORM\Column(name="module_data", type="text", length=65535, nullable=false)
     */
    private $moduleData;

    /**
     * @var bool
     *
     * @ORM\Column(name="module_status", type="boolean", nullable=false)
     */
    private $moduleStatus;

    public function getModuleId(): ?int
    {
        return $this->moduleId;
    }

    public function getModuleName(): ?string
    {
        return $this->moduleName;
    }

    public function setModuleName(string $moduleName): self
    {
        $this->moduleName = $moduleName;

        return $this;
    }

    public function getModuleData(): ?string
    {
        return $this->moduleData;
    }

    public function setModuleData(string $moduleData): self
    {
        $this->moduleData = $moduleData;

        return $this;
    }

    public function getModuleStatus(): ?bool
    {
        return $this->moduleStatus;
    }

    public function setModuleStatus(bool $moduleStatus): self
    {
        $this->moduleStatus = $moduleStatus;

        return $this;
    }


}
