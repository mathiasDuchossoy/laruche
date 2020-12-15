<?php


namespace App\Controller;


use App\Entity\Stock;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class StockController extends AbstractController
{
    public function __invoke(Request $request): Stock
    {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $stock = new Stock();
        $stock->file = $uploadedFile;

        return $stock;
    }
}
