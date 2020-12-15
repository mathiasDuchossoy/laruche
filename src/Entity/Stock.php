<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\StockController;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

//*             "security"="is_granted('ROLE_USER')",

/**
 * @ORM\Entity
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
 *         "get"
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
     * @ORM\ManyToOne(targetEntity=Gift::class, inversedBy="stock")
     */
    private $gift;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGift(): ?Gift
    {
        return $this->gift;
    }

    public function setGift(?Gift $gift): self
    {
        $this->gift = $gift;

        return $this;
    }
}
