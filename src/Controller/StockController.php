<?php


namespace App\Controller;


use App\Entity\Gift;
use App\Entity\Receiver;
use App\Entity\Stock;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

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

    /**
     * @Route(
     *     name="api_stocks_get_statistics",
     *     path="/api/stocks/{id}/statistics",
     *     methods={"GET"}
     * )
     */
    public function getStatistics(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            /** @var Stock $stock */
            if (null === $stock = $entityManager->getRepository(Stock::class)->find($id)) {
                throw new NotFoundHttpException('Stock not found for id ' . $id);
            }

            $statistics['gifts'] = $stock->getGifts()->count();

            $paysCounts = $entityManager->getRepository(Stock::class)->countPays($id);

            $statistics['nbPays'] = count($paysCounts);

            $avgPrices = $entityManager->getRepository(Stock::class)->avgPrices($id);

            $statistics['avgPrices'] = $avgPrices['avgPrices'];

            $gifts = $entityManager->getRepository(Gift::class)->findByPriceNotNull($id);

            $statistics['minPrice'] = array_shift($gifts)['price'];

            $maxPrice = end($gifts)['price'];
//            while(!is_float($maxPrice)) {
//                $maxPrice = prev($gifts)['price'];
//            }
            $statistics['maxPrice'] = $maxPrice;

            return $this->json($statistics);

        } catch (\Exception $exception) {
            return $this->json(['error' => $exception->getMessage()]);
        }
    }
}
