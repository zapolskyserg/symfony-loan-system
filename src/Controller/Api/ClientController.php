<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Loan\Application\Command\CreateClientCommand;
use App\Loan\Application\Service\CreateClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

#[Route('/api')]
final class ClientController extends AbstractController
{
    #[Route('/clients', name: 'api_client_create', methods: ['POST'])]
    public function create(Request $request, CreateClient $createClientService): JsonResponse
    {
        try {
            // 1. Отримуємо дані з тіла JSON-запиту
            $data = $request->toArray();

            // 2. Створюємо DTO з отриманих даних
            $command = new CreateClientCommand(
                name: $data['name'],
                age: $data['age'],
                regionCode: $data['regionCode'],
                income: $data['income'],
                score: $data['score'],
                creditHistoryStatus: $data['creditHistoryStatus'],
                pin: $data['pin'],
                email: $data['email'] ?? null,
                phone: $data['phone'] ?? null
            );

            // 3. Викликаємо наш сервіс-обробник
            ($createClientService)($command);
        } catch (Throwable $e) {
            // Проста обробка помилок (наприклад, якщо передали невалідний регіон)
            return new JsonResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }

        // 4. Повертаємо успішну відповідь
        return new JsonResponse(null, Response::HTTP_CREATED);
    }
}
