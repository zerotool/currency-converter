<?php

namespace App\Controller;

use App\Entity\CurrencyRate;
use App\Form\ConvertCurrency;
use App\Repository\CurrencyRateRepository;
use App\Utils\CurrencyRatesConverter;
use App\Utils\Exception\ConvertCurrencyValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RestController
 * @package App\Controller
 */
class RestController extends AbstractController
{
    /**
     * @var CurrencyRateRepository
     */
    private $currencyRateRepository;

    /**
     * RestController constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->currencyRateRepository = $entityManager->getRepository(CurrencyRate::class);
    }

    /**
     * @Route("/convert")
     */
    public function convertAction(Request $request)
    {
        try {
            $data = json_decode($request->getContent(), true);
            $form = $this->getConvertForm($data);
            $currencyRatesConverter = $this->getCurrencyRatesConverter();
            return $this->getConvertSuccessResponse(
                $currencyRatesConverter->convert(
                    $form->get(ConvertCurrency::FROM_CURRENCY_CODE)->getData(),
                    $form->get(ConvertCurrency::TO_CURRENCY_CODE)->getData(),
                    $form->get(ConvertCurrency::AMOUNT)->getData()
                )
            );
        } catch (\Exception $exception) {
            return $this->getConvertErrorResponse($exception->getMessage());
        }
    }

    /**
     * @return CurrencyRatesConverter
     */
    private function getCurrencyRatesConverter(){
        return new CurrencyRatesConverter(
            $this->getParameter('app.currency.base_currency'),
            $this->currencyRateRepository
        );
    }

    /**
     * @param $data
     * @return \Symfony\Component\Form\FormInterface
     * @throws ConvertCurrencyValidationException
     */
    private function getConvertForm($data)
    {
        $form = $this->createForm(ConvertCurrency::class, null, ['csrf_protection' => false]);
        if (!$form->submit($data)->isValid()) {
            foreach ($form->getErrors(true) as $error) {
                throw new ConvertCurrencyValidationException($error->getCause());
            }
        }
        return $form;
    }

    /**
     * @param $amountConverted
     * @return JsonResponse
     */
    private function getConvertSuccessResponse($amountConverted)
    {
        return $this->json([
            'success' => true,
            'data' => [
                'amount_converted' => $amountConverted,
            ],
        ], JsonResponse::HTTP_OK);
    }

    /**
     * @param $error
     * @return JsonResponse
     */
    private function getConvertErrorResponse($error)
    {
        return $this->json([
            'success' => false,
            'error' => $error
        ], JsonResponse::HTTP_BAD_REQUEST);
    }
}
