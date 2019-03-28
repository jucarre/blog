<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the private '.service_locator.75cXSiM' shared service.

return $this->privates['.service_locator.75cXSiM'] = new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($this->getService, [
    'manager' => ['services', 'doctrine.orm.default_entity_manager', 'getDoctrine_Orm_DefaultEntityManagerService.php', true],
    'passwordEncoder' => ['services', 'security.password_encoder', 'getSecurity_PasswordEncoderService.php', true],
    'tokenSendler' => ['privates', 'App\\Service\\TokenSendler', 'getTokenSendlerService.php', true],
]);
