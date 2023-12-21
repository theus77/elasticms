<?php

declare(strict_types=1);

namespace EMS\ClientHelperBundle\Security\Saml;

class SamlConfig
{
    final public const PATH_SAML = '/saml';
    final public const ROUTE_METADATA = 'emsch_saml_metadata';
    final public const ROUTE_LOGIN = 'emsch_saml_login';
    final public const ROUTE_ACS = 'emsch_saml_acs';

    /**
     * @param array<mixed> $config
     */
    public function __construct(private readonly array $config)
    {
    }

    public function isEnabled(): bool
    {
        return $this->config['enabled'] ?? false;
    }

    public function spPublicKey(): string
    {
        return $this->config['sp']['public_key'];
    }

    public function spPrivateKey(): string
    {
        return $this->config['sp']['private_key'];
    }

    public function idpPublicKey(): string
    {
        return $this->config['idp']['public_key'];
    }

    public function spEntityId(): string
    {
        return $this->config['sp']['entity_id'];
    }

    public function idpEntityId(): string
    {
        return $this->config['idp']['entity_id'];
    }

    public function idpSSO(): string
    {
        return $this->config['idp']['sso'];
    }

    /**
     * @return array<string, mixed>
     */
    public function security(): array
    {
        return $this->config['security'] ?? [];
    }
}
