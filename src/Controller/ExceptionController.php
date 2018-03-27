<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

/**
 * @author Martin Pánek <kontakt@martinpanek.cz>
 */
class ExceptionController extends \Symfony\Bundle\TwigBundle\Controller\ExceptionController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Debug\Exception\FlattenException $exception
     * @param \Symfony\Component\HttpKernel\Log\DebugLoggerInterface|null $logger
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Request $request, FlattenException $exception, DebugLoggerInterface $logger = null)
    {
        if (strpos($request->getRequestUri(), '/api/') !== false) {
            return new JsonResponse([
                'type' =>$exception->getClass(),
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->showAction($request, $exception, $logger);
    }

}
