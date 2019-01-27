<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DashboardUsers
 *
 * @ORM\Table(name="dashboard_users")
 * @ORM\Entity
 */
class DashboardUsers
{
    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="user_name", type="text", length=65535, nullable=false)
     */
    private $userName;

    /**
     * @var string
     *
     * @ORM\Column(name="user_email", type="text", length=65535, nullable=false)
     */
    private $userEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="user_password", type="text", length=65535, nullable=false)
     */
    private $userPassword;

    /**
     * @var bool
     *
     * @ORM\Column(name="user_status", type="boolean", nullable=false)
     */
    private $userStatus;

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    public function getUserEmail(): ?string
    {
        return $this->userEmail;
    }

    public function setUserEmail(string $userEmail): self
    {
        $this->userEmail = $userEmail;

        return $this;
    }

    public function getUserPassword(): ?string
    {
        return $this->userPassword;
    }

    public function setUserPassword(string $userPassword): self
    {
        $this->userPassword = $userPassword;

        return $this;
    }

    public function getUserStatus(): ?bool
    {
        return $this->userStatus;
    }

    public function setUserStatus(bool $userStatus): self
    {
        $this->userStatus = $userStatus;

        return $this;
    }


}
