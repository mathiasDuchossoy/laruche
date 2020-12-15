<?php


namespace App\Controller;


use App\Entity\Gift;
use App\Entity\Receiver;
use App\Entity\Stock;
use League\Csv\Reader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class StockController extends AbstractController
{
    public function __invoke(Request $request): Stock
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        if ('text/csv' !== $uploadedFile->getClientMimeType()) {
            throw new BadRequestHttpException('csv file is required');
        }

        $stock = new Stock();
        $stock->file = $uploadedFile;

        $csv = Reader::createFromPath($uploadedFile->getPathname(), 'r');
        $csv->setHeaderOffset(0);

        $records = $csv->getRecords(); //returns all the CSV records as an Iterator object

        foreach ($records as $record) {
            $gift = (new Gift())
                ->setId($record['gift_uuid'])
                ->setCode($record['gift_code'])
                ->setDescription($record['gift_description'])
                ->setPrice($record['gift_price']);

            $receiver = (new Receiver())
                ->setId($record['receiver_uuid'])
                ->setFirstName($record['receiver_first_name'])
                ->setLastName($record['receiver_last_name'])
                ->setCountryCode($record['receiver_country_code']);

            $gift->addReceiver($receiver);

            $stock->addGift($gift);
        }

        return $stock;
    }
}
