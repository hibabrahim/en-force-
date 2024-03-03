<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GoogleController extends AbstractController
{
    /**
     * Start the "connect" process with Google
     *
     * @Route("/connect/google", name="connect_google")
     * @param ClientRegistry $clientRegistry
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function connectAction(ClientRegistry $clientRegistry)
    {
        // Redirect to Google for authentication
        return $clientRegistry
            ->getClient('google') // Client name defined in knpu_oauth2_client.yaml
            ->redirect();
    }

    /**
     * Callback URL after Google authentication
     *
     * @Route("/connect/google/check", name="connect_google_check")
     *  @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function connectCheckAction(Request $request)
    {
        // Check if a user is authenticated
        if (!$this->getUser()) {
            // Return JSON response if user is not found
            return new JsonResponse(['status' => false, 'message' => 'User not found!']);
        } else {
            // Redirect to the index route if user is authenticated
            return $this->redirectToRoute('index');
        }
    }
}
