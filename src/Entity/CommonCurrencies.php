<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommonCurrencies
 *
 * @ORM\Table(name="common_currencies")
 * @ORM\Entity
 */
class CommonCurrencies
{
    /**
     * @var int
     *
     * @ORM\Column(name="currency_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $currencyId;

    /**
     * @var string
     *
     * @ORM\Column(name="currency_name", type="text", length=65535, nullable=false)
     */
    private $currencyName;

    /**
     * @var string
     *
     * @ORM\Column(name="currency_code", type="text", length=65535, nullable=false)
     */
    private $currencyCode;

    /**
     * @var string
     *
     * @ORM\Column(name="currency_symbol", type="text", length=65535, nullable=false)
     */
    private $currencySymbol;

    public function getCurrencyId(): ?int
    {
        return $this->currencyId;
    }

    public function getCurrencyName(): ?string
    {
        return $this->currencyName;
    }

    public function setCurrencyName(string $currencyName): self
    {
        $this->currencyName = $currencyName;

        return $this;
    }

    public function getCurrencyCode(): ?string
    {
        return $this->currencyCode;
    }

    public function setCurrencyCode(string $currencyCode): self
    {
        $this->currencyCode = $currencyCode;

        return $this;
    }

    public function getCurrencySymbol(): ?string
    {
        return $this->currencySymbol;
    }

    public function setCurrencySymbol(string $currencySymbol): self
    {
        $this->currencySymbol = $currencySymbol;

        return $this;
    }


}
