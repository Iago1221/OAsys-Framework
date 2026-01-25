<?php

namespace Framework\Interface\Application\Auth;

use Framework\Interface\Domain\Modulo\Modulo;
use Framework\Interface\Domain\Modulo\ModuloItem;
use Framework\Interface\Domain\Usuario\Usuario;
use Framework\Interface\Domain\Usuario\UsuarioModulo;
use Framework\Interface\Domain\Usuario\UsuarioModuloItem;
use Framework\Interface\Infrastructure\Persistence\Sistema\Usuario\UsuarioModuloItemRepository;
use Framework\Interface\Infrastructure\Persistence\Sistema\Usuario\UsuarioModuloRepository;

class AuthorizationService
{
    protected UsuarioModuloRepository $usuarioModuloRepository;
    protected UsuarioModuloItemRepository $usuarioModuloItemRepository;

    public function __construct(UsuarioModuloRepository $usuarioModuloRepository, UsuarioModuloItemRepository $usuarioModuloItemRepository)
    {
        $this->usuarioModuloRepository = $usuarioModuloRepository;
        $this->usuarioModuloItemRepository = $usuarioModuloItemRepository;
    }

    public function podeAcessarModulo($usuarioId, Modulo $modulo): bool
    {
        if ($modulo->isSituacao(Modulo::SITUACAO_INATIVO)) {
            return false;
        }

        $this->usuarioModuloRepository->filterBy(['usuario' => $usuarioId, 'modulo' => $modulo->getId()]);
        $result = $this->usuarioModuloRepository->get();

        if (!isset($result) || !isset($result[0])) {
            return true;
        }

        /** @var UsuarioModulo $permissao */
        $permissao = $result[0];

        if (!isset($permissao)) {
            return true;
        }

        if (!$permissao->getPermitido()) {
            return false;
        }

        return true;
    }

    public function podeAcessarItem($usuarioId, ModuloItem $moduloItem): bool
    {
        if ($moduloItem->isSituacao(ModuloItem::SITUACAO_INATIVO)) {
            return false;
        }

        $this->usuarioModuloItemRepository->filterBy(['usuario' => $usuarioId, 'moduloItem' => $moduloItem->getId()]);
        $result = $this->usuarioModuloItemRepository->get();

        if (!isset($result)) {
            return true;
        }

        /** @var UsuarioModuloItem $permissao */
        $permissao = $result[0];

        if (!isset($permissao) || !isset($result[0])) {
            return true;
        }

        if (!$permissao->getPermitido()) {
            return false;
        }

        return true;
    }
}
