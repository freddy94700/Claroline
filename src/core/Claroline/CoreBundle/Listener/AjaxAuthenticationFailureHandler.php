<?php

namespace Claroline\CoreBundle\Listener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationFailureHandler;
use JMS\DiExtraBundle\Annotation as DI;

class AjaxAuthenticationFailureHandler extends DefaultAuthenticationFailureHandler
{
   /**
    * {@inheritDoc}
    */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        if ($request->isXmlHttpRequest()) {
            $json = array(
                'has_error' => true,
                'error' => $exception->getMessage()
            );

            return new JsonResponse($json);
        }

        return parent::onAuthenticationFailure($request, $exception);
    }
}