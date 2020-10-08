<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
class PasswordUpdate
{


    private $oldpassword;

    /**
     * @Assert\Length(min=6, minMessage="6 caractères minimum !")
     */
    private $newpassword;

    /**
     * @Assert\EqualTo(propertyPath="newpassword", message="Confirmation du mot de incorrrecte !")
     */
    private $passwordConfirm;



    public function getOldpassword(): ?string
    {
        return $this->oldpassword;
    }

    public function setOldpassword(string $oldpassword): self
    {
        $this->oldpassword = $oldpassword;

        return $this;
    }

    public function getNewpassword(): ?string
    {
        return $this->newpassword;
    }

    public function setNewpassword(string $newpassword): self
    {
        $this->newpassword = $newpassword;

        return $this;
    }

    public function getPasswordConfirm(): ?string
    {
        return $this->passwordConfirm;
    }

    public function setPasswordConfirm(string $passwordConfirm): self
    {
        $this->passwordConfirm = $passwordConfirm;

        return $this;
    }
}
