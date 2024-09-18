<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\InventoryTransactionRepository;

#[ORM\Entity(repositoryClass: InventoryTransactionRepository::class)]
class InventoryTransaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

   // generate Product Entity here
    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $product;

    #[ORM\Column(type: 'float')]
    private ?float $price = null;

    // generate Inventory Entity here
    #[ORM\ManyToOne(targetEntity: Inventory::class)]
    #[ORM\JoinColumn(nullable: true)]
    private $inventory;

   // generate Supplier Entity here
   #[ORM\ManyToOne(targetEntity: Supplier::class)]
    #[ORM\JoinColumn(nullable: true)]
    private $supplier;

    // generate Customer Entity here
    #[ORM\ManyToOne(targetEntity: Customer::class)]
    #[ORM\JoinColumn(nullable: true)]
    private $customer;

    #[ORM\Column(length: 255)]
    private ?string $transactionType = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $transactionDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $customerName = null;

    #[ORM\Column(length: 512, nullable: true)]
    private ?string $productName = '';

    #[ORM\Column(length: 1024, nullable: true)]
    private ?string $fileName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nameInventory = '';

    #[ORM\Column(type: 'boolean')]
    private ?bool $isNew = null;

    #[ORM\Column(type: 'integer')]
    private ?int $quantity = null;

    #[ORM\Column(type: 'integer')]
    private ?int $exchangedQuantity = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?float $unitPrice = null;

    #[ORM\Column(type: 'decimal', precision: 20, scale: 2)]
    private ?float $totalPrice = 0;

    #[ORM\Column(type: 'decimal', precision: 20, scale: 2)]
    private ?float $totalBeforeDecrease = null;

    #[ORM\Column(type: 'decimal', precision: 20, scale: 2)]
    private ?float $totalAfterDecrease = null;

    #[ORM\Column(type: 'decimal', precision: 20, scale: 2)]
    private ?float $discount = null;

    #[ORM\Column(length: 255)]
    private ?string $reference = null;

    #[ORM\Column(length: 255)]
    private ?string $remarks = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(length: 255)]
    private ?string $createdBy = null;


    #[ORM\Column(length: 255)]
    private ?string $updatedBy = null;



    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of product
     */ 
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set the value of product
     *
     * @return  self
     */ 
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get the value of inventory
     */ 
    public function getInventory()
    {
        return $this->inventory;
    }

    /**
     * Set the value of inventory
     *
     * @return  self
     */ 
    public function setInventory($inventory)
    {
        $this->inventory = $inventory;

        return $this;
    }

    /**
     * Get the value of supplier
     */ 
    public function getSupplier()
    {
        return $this->supplier;
    }

    /**
     * Set the value of supplier
     *
     * @return  self
     */ 
    public function setSupplier($supplier)
    {
        $this->supplier = $supplier;

        return $this;
    }

    /**
     * Get the value of customer
     */ 
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set the value of customer
     *
     * @return  self
     */ 
    public function setCustomer($customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get the value of transactionType
     */ 
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * Set the value of transactionType
     *
     * @return  self
     */ 
    public function setTransactionType($transactionType)
    {
        $this->transactionType = $transactionType;

        return $this;
    }

    /**
     * Get the value of transactionDate
     */ 
    public function getTransactionDate()
    {
        return $this->transactionDate->format('d-m-Y');
        ;
    }

    /**
     * Set the value of transactionDate
     *
     * @return  self
     */ 
    public function setTransactionDate($transactionDate)
    {
        $this->transactionDate = $transactionDate;

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
     * Get the value of unitPrice
     */ 
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * Set the value of unitPrice
     *
     * @return  self
     */ 
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    /**
     * Get the value of totalPrice
     */ 
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * Set the value of totalPrice
     *
     * @return  self
     */ 
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    /**
     * Get the value of reference
     */ 
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set the value of reference
     *
     * @return  self
     */ 
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get the value of remarks
     */ 
    public function getRemarks()
    {
        return $this->remarks;
    }

    /**
     * Set the value of remarks
     *
     * @return  self
     */ 
    public function setRemarks($remarks)
    {
        $this->remarks = $remarks;

        return $this;
    }

    /**
     * Get the value of status
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */ 
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of createdBy
     */ 
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set the value of createdBy
     *
     * @return  self
     */ 
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get the value of updatedBy
     */ 
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * Set the value of updatedBy
     *
     * @return  self
     */ 
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;

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
     * Get the value of discount
     */ 
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set the value of discount
     *
     * @return  self
     */ 
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get the value of isNew
     */ 
    public function getIsNew()
    {
        return $this->isNew;
    }

    /**
     * Set the value of isNew
     *
     * @return  self
     */ 
    public function setIsNew($isNew)
    {
        $this->isNew = $isNew;

        return $this;
    }

    /**
     * Get the value of totalBeforeDecrease
     */ 
    public function getTotalBeforeDecrease()
    {
        return $this->totalBeforeDecrease;
    }

    /**
     * Set the value of totalBeforeDecrease
     *
     * @return  self
     */ 
    public function setTotalBeforeDecrease($totalBeforeDecrease)
    {
        $this->totalBeforeDecrease = $totalBeforeDecrease;

        return $this;
    }

    /**
     * Get the value of totalAfterDecrease
     */ 
    public function getTotalAfterDecrease()
    {
        return $this->totalAfterDecrease;
    }

    /**
     * Set the value of totalAfterDecrease
     *
     * @return  self
     */ 
    public function setTotalAfterDecrease($totalAfterDecrease)
    {
        $this->totalAfterDecrease = $totalAfterDecrease;

        return $this;
    }

    /**
     * Get the value of customerName
     */ 
    public function getCustomerName()
    {
        return $this->customerName;
    }

    /**
     * Set the value of customerName
     *
     * @return  self
     */ 
    public function setCustomerName($customerName)
    {
        $this->customerName = $customerName;

        return $this;
    }

    /**
     * Get the value of fileName
     */ 
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set the value of fileName
     *
     * @return  self
     */ 
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get the value of exchangedQuantity
     */ 
    public function getExchangedQuantity()
    {
        return $this->exchangedQuantity;
    }

    /**
     * Set the value of exchangedQuantity
     *
     * @return  self
     */ 
    public function setExchangedQuantity($exchangedQuantity)
    {
        $this->exchangedQuantity = $exchangedQuantity;

        return $this;
    }

    /**
     * Get the value of nameInventory
     */ 
    public function getNameInventory()
    {
        return $this->nameInventory;
    }

    /**
     * Set the value of nameInventory
     *
     * @return  self
     */ 
    public function setNameInventory($nameInventory)
    {
        $this->nameInventory = $nameInventory;

        return $this;
    }

    /**
     * Get the value of productName
     */ 
    public function getProductName()
    {
        return $this->productName;
    }

    /**
     * Set the value of productName
     *
     * @return  self
     */ 
    public function setProductName($productName)
    {
        $this->productName = $productName;

        return $this;
    }
}
