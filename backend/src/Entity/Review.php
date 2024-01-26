<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\CarReviewController;
use App\Repository\ReviewRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
#[ApiResource(
    operations: [
        new Post(),
        new Put(),
        new Delete(),
        new GetCollection(
            uriTemplate: '/cars/{car}/reviews',
            uriVariables: [
                'car' => new Link(fromProperty: 'reviews', fromClass: Car::class),
            ],
            requirements: ['car', '[1-9]\d*'],
            controller: CarReviewController::class . '::index',
            queryParameterValidationEnabled: true,
        ),
    ],
    routePrefix: 'v1',
    normalizationContext: ['groups' => ['review:read']]
)]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['review:read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Assert\Range(min: 1, max: 10)]
    #[Groups(['review:read'])]
    private ?int $rate = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Length(min: 10, max: 1000)]
    #[Groups(['review:read'])]
    private ?string $content = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['review:read'])]
    #[Assert\NotBlank]
    private ?Car $car = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(int $rate): static
    {
        $this->rate = $rate;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): static
    {
        $this->car = $car;

        return $this;
    }
}
