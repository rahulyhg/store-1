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
     * @ORM\Column(name="module_description", type="text", length=65535, nullable=false)
     */
    private $moduleDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="module_data", type="text", length=65535, nullable=false)
     */
    private $moduleData;

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

    public function getModuleDescription(): ?string
    {
        return $this->moduleDescription;
    }

    public function setModuleDescription(string $moduleDescription): self
    {
        $this->moduleDescription = $moduleDescription;

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


}
