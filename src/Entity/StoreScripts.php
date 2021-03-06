<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StoreScripts
 *
 * @ORM\Table(name="store_scripts")
 * @ORM\Entity
 */
class StoreScripts
{
    /**
     * @var int
     *
     * @ORM\Column(name="script_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $scriptId;

    /**
     * @var string
     *
     * @ORM\Column(name="script_name", type="text", length=65535, nullable=false)
     */
    private $scriptName;

    /**
     * @var string
     *
     * @ORM\Column(name="script_data", type="text", length=65535, nullable=false)
     */
    private $scriptData;

    /**
     * @var bool
     *
     * @ORM\Column(name="script_status", type="boolean", nullable=false)
     */
    private $scriptStatus;

    public function getScriptId(): ?int
    {
        return $this->scriptId;
    }

    public function getScriptName(): ?string
    {
        return $this->scriptName;
    }

    public function setScriptName(string $scriptName): self
    {
        $this->scriptName = $scriptName;

        return $this;
    }

    public function getScriptData(): ?string
    {
        return $this->scriptData;
    }

    public function setScriptData(string $scriptData): self
    {
        $this->scriptData = $scriptData;

        return $this;
    }

    public function getScriptStatus(): ?bool
    {
        return $this->scriptStatus;
    }

    public function setScriptStatus(bool $scriptStatus): self
    {
        $this->scriptStatus = $scriptStatus;

        return $this;
    }


}
