<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ApiResource(normalizationContext: ['groups' => ['product:read']], denormalizationContext: ['groups' => ['product:write']])]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $flatName = '';

    #[ORM\Column(type: 'float')]
    private ?float $price = null;

    #[ORM\Column(type: 'float')]
    private ?float $quantity = null;

    #[ORM\Column(type: 'float')]
    private ?float $priceBuy = null;

    #[ORM\Column(length: 1024, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(length: 125, nullable: true)]
    private ?string $unit = null;

    #[ORM\Column(length: 125, nullable: true)]
    private ?string $code = null;

    #[ORM\Column(length: 125, nullable: true)]
    private ?string $standardCode = null;

    #[ORM\Column(length: 125, nullable: true)]
    private ?string $mainCode = null;

    #[ORM\Column(length: 1024, nullable: true)]
    private ?string $largeImage = null;

    #[ORM\Column(type: 'integer', name: 'category_id', nullable: true)]
    private int $categoryId;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private $category;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }



    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getLargeImage(): ?string
    {
        return $this->largeImage;
    }

    public function setLargeImage(?string $largeImage): static
    {
        $this->largeImage = $largeImage;

        return $this;
    }

    /**
     * Get the value of price
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set the value of price
     *
     * @return  self
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get /*
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set /*
     *
     * @return  self
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get the value of categoryId
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * Set the value of categoryId
     *
     * @return  self
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    /**
     * Get the value of priceBuy
     */ 
    public function getPriceBuy()
    {
        return $this->priceBuy;
    }

    /**
     * Set the value of priceBuy
     *
     * @return  self
     */ 
    public function setPriceBuy($priceBuy)
    {
        $this->priceBuy = $priceBuy;

        return $this;
    }

    /**
     * Get the value of quantity
     */ 
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set the value of quantity
     *
     * @return  self
     */ 
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get the value of unit
     */ 
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set the value of unit
     *
     * @return  self
     */ 
    public function setUnit($unit)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get the value of code
     */ 
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set the value of code
     *
     * @return  self
     */ 
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get the value of mainCode
     */ 
    public function getMainCode()
    {
        return $this->mainCode;
    }

    /**
     * Set the value of mainCode
     *
     * @return  self
     */ 
    public function setMainCode($mainCode)
    {
        // get 4 character of code and check null
        if ($mainCode == null) {
            $this->mainCode = null;
            return $this;
        }
        $this->mainCode = substr($mainCode, 0, 4);

        return $this;
    }

    /**
     * Get the value of flatName
     */ 
    public function getFlatName()
    {
        return $this->flatName;
    }

    /**
     * Set the value of flatName
     *
     * @return  self
     */ 
    public function setFlatName($flatName)
    {
        $this->flatName = $flatName;

        return $this;
    }

    /**
     * Get the value of standardCode
     */ 
    public function getStandardCode()
    {
        return $this->standardCode;
    }

    /**
     * Set the value of standardCode
     *
     * @return  self
     */ 
    public function setStandardCode($standardCode)
    {
        $this->standardCode = $standardCode;

        return $this;
    }
}
