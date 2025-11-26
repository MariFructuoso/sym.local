<?php
namespace App\Entity;

Interface IEntity {
    public function toArray(): array;
    public function getId(): ?int;
}
?>