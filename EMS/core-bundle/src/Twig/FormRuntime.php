<?php

declare(strict_types=1);

namespace EMS\CoreBundle\Twig;

use EMS\CoreBundle\Core\Form\FormManager;
use EMS\CoreBundle\Entity\Form;

class FormRuntime
{
    public function __construct(protected FormManager $formManager)
    {
    }

    public function getFormByName(string $name): ?Form
    {
        $form = $this->formManager->getByItemName($name);
        if (null !== $form && !$form instanceof Form) {
            throw new \RuntimeException('Unexpected non-Form entity');
        }

        return $form;
    }
}
