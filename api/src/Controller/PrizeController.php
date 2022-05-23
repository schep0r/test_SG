<?php

namespace App\Controller;

use App\Entity\Prize;
use App\Exception\NoPendingPrizeException;
use App\Service\PrizeService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PrizeController extends AbstractController
{
    public function __construct(private PrizeService $prizeService)
    {
    }

    public function getPrize(Request $request): Response
    {
        $user = $this->getUser($request);
        $em = $this->getEm();

        /** @var Prize $prize */
        if ($prize = $em->getRepository(Prize::class)->findLastPendingPrize($user)) {
            $this->prizeService->accept($prize, $user);
            $em->flush();
        }

        $prize = $this->prizeService->generatePrize($user);

        $em->persist($prize);
        $em->flush();

        return new JsonResponse($this->getPrizeResponseArray($prize));
    }

    public function declinePrize(Request $request): Response
    {
        $user = $this->getUser($request);
        $em = $this->getEm();

        /** @var Prize $prize */
        if (!$prize = $em->getRepository(Prize::class)->findLastPendingPrize($user)) {
            throw new NoPendingPrizeException("No prize for decline");
        }

        $prize->setStatus(Prize::STATUS_DECLINE);
        $em->flush();

        return new JsonResponse(["status" => "OK"]);
    }
}
