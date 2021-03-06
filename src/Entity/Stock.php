<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\StockController;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Repository\StockRepository;

//*             "security"="is_granted('ROLE_USER')",

/**
 * @ORM\Entity(repositoryClass=StockRepository::class)
 * @ApiResource(
 *     iri="http://schema.org/MediaObject",
 *     normalizationContext={
 *         "groups"={"stock_read"}
 *     },
 *     collectionOperations={
 *         "post"={
 *             "controller"=StockController::class,
 *             "deserialize"=false,
 *
 *             "validation_groups"={"Default", "stock_create"},
 *             "openapi_context"={
 *                 "requestBody"={
 *                     "content"={
 *                         "multipart/form-data"={
 *                             "schema"={
 *                                 "type"="object",
 *                                 "properties"={
 *                                     "file"={
 *                                         "type"="string",
 *                                         "format"="binary"
 *                                     }
 *                                 }
 *                             }
 *                         }
 *                     }
 *                 }
 *             }
 *         },
 *         "get"
 *     },
 *     itemOperations={
 *         "get",
 *         "api_stocks_get_statistics"={"route_name"="api_stocks_get_statistics"},
 *     }
 * )
 * @Vich\Uploadable
 */
class Stock
{
    /**
     * @var string|null
     *
     * @ApiProperty(iri="http://schema.org/contentUrl")
     * @Groups({"stock_read"})
     */
    public $contentUrl;
    /**
     * @var File|null
     *
     * @Assert\NotNull(groups={"stock_create"})
     * @Vich\UploadableField(mapping="stock", fileNameProperty="filePath")
     */
    public $file;
    /**
     * @var string|null
     *
     * @ORM\Column(nullable=true)
     */
    public $filePath;
    /**
     * @var int|null
     *
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @ORM\Id
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity=Gift::class, mappedBy="stock", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $gifts;

    public function __construct()
    {
        $this->gifts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Gift[]
     */
    public function getGifts(): Collection
    {
        return $this->gifts;
    }

    public function addGift(Gift $gift): self
    {
        if (!$this->gifts->contains($gift)) {
            $this->gifts[] = $gift;
            $gift->setStock($this);
        }

        return $this;
    }

    public function removeGift(Gift $gift): self
    {
        if ($this->gifts->removeElement($gift)) {
            // set the owning side to null (unless already changed)
            if ($gift->getStock() === $this) {
                $gift->setStock(null);
            }
        }

        return $this;
    }
}
