<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommonLanguages
 *
 * @ORM\Table(name="common_languages")
 * @ORM\Entity
 */
class CommonLanguages
{
    /**
     * @var int
     *
     * @ORM\Column(name="language_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $languageId;

    /**
     * @var string
     *
     * @ORM\Column(name="language_name", type="text", length=65535, nullable=false)
     */
    private $languageName;

    /**
     * @var string
     *
     * @ORM\Column(name="language_code", type="text", length=65535, nullable=false)
     */
    private $languageCode;

    public function getLanguageId(): ?int
    {
        return $this->languageId;
    }

    public function getLanguageName(): ?string
    {
        return $this->languageName;
    }

    public function setLanguageName(string $languageName): self
    {
        $this->languageName = $languageName;

        return $this;
    }

    public function getLanguageCode(): ?string
    {
        return $this->languageCode;
    }

    public function setLanguageCode(string $languageCode): self
    {
        $this->languageCode = $languageCode;

        return $this;
    }


}
