<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommonLogs
 *
 * @ORM\Table(name="common_logs")
 * @ORM\Entity
 */
class CommonLogs
{
    /**
     * @var int
     *
     * @ORM\Column(name="log_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $logId;

    /**
     * @var string
     *
     * @ORM\Column(name="log_data", type="text", length=65535, nullable=false)
     */
    private $logData;

    /**
     * @var string
     *
     * @ORM\Column(name="log_datetime", type="text", length=65535, nullable=false)
     */
    private $logDatetime;

    public function getLogId(): ?int
    {
        return $this->logId;
    }

    public function getLogData(): ?string
    {
        return $this->logData;
    }

    public function setLogData(string $logData): self
    {
        $this->logData = $logData;

        return $this;
    }

    public function getLogDatetime(): ?string
    {
        return $this->logDatetime;
    }

    public function setLogDatetime(string $logDatetime): self
    {
        $this->logDatetime = $logDatetime;

        return $this;
    }


}
