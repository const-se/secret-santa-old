<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Participant;
use AppBundle\Form\Type\Participant\RegistrationType;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @Config\Route("/api")
 */
class ApiController extends Controller
{
    /**
     * @Config\Route("/validation/registration", name = "api_validate_registration", options = {"expose": true})
     * @param Request $request
     * @return JsonResponse
     * @throws BadRequestHttpException
     */
    public function validateRegistration(Request $request): JsonResponse
    {
        $form = $this
            ->createForm(
                RegistrationType::class, new Participant(),
                ['recaptcha' => false, 'csrf_protection' => false]
            )->handleRequest($request);

        return $this->renderValidationResponse($form);
    }

    /**
     * @param string $message
     * @param Exception|null $previous
     * @return BadRequestHttpException
     */
    protected function createBadRequestException($message = 'Bad Request', Exception $previous = null): BadRequestHttpException
    {
        return new BadRequestHttpException($message, $previous);
    }

    /**
     * @param FormInterface $form
     * @return array
     */
    protected function getFormErrors(FormInterface $form): array
    {
        $errors = [];

        foreach ($form->getErrors() as $error) {
            $errors['errors'][] = $error->getMessage();
        }

        /** @var FormInterface $child */
        foreach ($form as $child) {
            if (!$child->isValid()) {
                $errors['children'][$child->getName()] = $this->getFormErrors($child);
            }
        }

        return $errors;
    }

    /**
     * @param FormInterface $form
     * @return JsonResponse
     * @throws BadRequestHttpException
     */
    protected function renderValidationResponse(FormInterface $form): JsonResponse
    {
        if ($form->isSubmitted()) {
            return new JsonResponse(['valid' => $form->isValid(), 'errors' => $this->getFormErrors($form)]);
        }

        throw $this->createBadRequestException();
    }
}
