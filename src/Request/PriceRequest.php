<?php

declare(strict_types=1);

namespace App\Request;

use App\Entity\Category;
use Symfony\Component\Validator\Constraints as Assert;

class PriceRequest
{
    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    private int $price;

    #[Assert\NotBlank]
    #[Assert\Currency]
    private string $currency;

    #[Assert\NotBlank]
    #[Assert\Length(min: 1)]
    private string $variant;

    #[Assert\NotBlank]
    #[Assert\Length(min: 1)]
    private string $product;

    #[Assert\NotBlank]
    #[Assert\Choice(choices: Category::VALUES)]
    private string $category;

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function setVariant(string $variant): self
    {
        $this->variant = $variant;

        return $this;
    }

    public function setProduct(string $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getVariant(): string
    {
        return $this->variant;
    }

    public function getProduct(): string
    {
        return $this->product;
    }

    public function getCategory(): string
    {
        return $this->category;
    }
}
