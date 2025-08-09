<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Loan\Application\Command\GenerateLoanOffersCommand;
use App\Loan\Application\Command\IssueLoanCommand;
use App\Loan\Application\Service\GenerateLoanOffers;
use App\Loan\Application\Service\IssueLoan;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
final class LoanController extends AbstractController
{
    #[Route('/clients/{clientId}/loan-offers', name: 'api_client_loan_offers', methods: ['GET'])]
    public function getOffers(string $clientId, GenerateLoanOffers $generateLoanOffersService): JsonResponse
    {
        $command = new GenerateLoanOffersCommand($clientId);
        $result = ($generateLoanOffersService)($command);

        if (!$result->isEligible) {
            return $this->json(
                ['message' => $result->reason],
                Response::HTTP_FORBIDDEN // 403 Forbidden - клієнт не має права на кредит
            );
        }

        return $this->json($result->offers);
    }

    #[Route('/loans/issue', name: 'api_loan_issue', methods: ['POST'])]
    public function issue(Request $request, IssueLoan $issueLoanService): JsonResponse
    {
        $data = $request->toArray();

        $command = new IssueLoanCommand(
            clientId: $data['clientId'],
            productName: $data['productName'],
            amount: $data['amount'],
            rate: $data['rate']
        );

        ($issueLoanService)($command);

        return new JsonResponse(
            ['status' => 'success', 'message' => 'Loan issued successfully and notification sent.'],
            Response::HTTP_OK
        );
    }
}
