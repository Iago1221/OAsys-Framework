<?php

namespace Framework\Infrastructure\MVC\Model;

class StatusModel extends Model
{
    const SITUACAO_ATIVO   = 1,
          SITUACAO_INATIVO = 2;

    protected $situacao;

    public function getSituacao()
    {
        return $this->situacao;
    }

    public function setSituacao($situacao)
    {
        $this->situacao = $situacao;
    }

    public function toggleSituacao() {
        if ($this->situacao == self::SITUACAO_INATIVO) {
            $this->setSituacao(self::SITUACAO_ATIVO);
            return;
        }

        $this->setSituacao(self::SITUACAO_INATIVO);
    }
}
