<?php declare(strict_types=1);

namespace App\Service\EventListener;

use App\Response\JsonResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\Security\Core\Security;

/**
 * @author Martin Pánek <kontakt@martinpanek.cz>
 */
class JsonResponseTransformerListener
{

    /**
     * @var \Symfony\Component\Security\Core\Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        /** @var \App\Response\JsonResponse $response */
        $response = $event->getResponse();

        if ($response instanceof JsonResponse) {
            $roles = $this->security->getUser() ? $this->security->getUser()->getRoles() : ["ROLE_UNAUTHENTICATED"];
            $response->convertToJson($roles);
//            $event->getResponse()->setContent(json_encode(['roles' => $roles, 'user' => json_encode($this->security->getUser())]));
        }
    }

}