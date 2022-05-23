<?php

namespace App\Controller;

use App\Entity\Prize;
use App\Exception\NoPendingPrizeException;
use App\Service\ConvertService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ConvertController extends AbstractController
{
    public function __construct(private ConvertService $convertService)
    {
    }

    public function update(Request $request): Response
    {
        $user = $this->getUser($request);
        $em = $this->getEm();

        /** @var Prize $prize */
        if (!$prize = $em->getRepository(Prize::class)->findLastPendingPrize($user)) {
            throw new NoPendingPrizeException("No prize for convert");
        }

        $pointsPrize = $this->convertService->convertMoneyToPoints($prize);

        $em->persist($pointsPrize);
        $em->flush();

        return new JsonResponse($this->getPrizeResponseArray($pointsPrize));
    }
}
