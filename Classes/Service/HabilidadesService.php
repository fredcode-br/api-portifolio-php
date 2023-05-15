<?php

    namespace Service;

    use Repository\HabilidadesRepository;
    use Util\ConstantesGenericasUtil;
    use InvalidArgumentException;

    class HabilidadesService{
        public const    TABELA = 'habilidades';
        public const    RECURSOS_GET = ['listar'];
        public const    RECURSOS_DELETE = ['deletar'];
        public const    RECURSOS_POST = ['cadastrar'];
        public const    RECURSOS_PUT = ['atualizar'];
        
        
        private array $dados;
        private array $dadosCorpoRequest = [];

        private object $HabilidadesRepository;

        public function __construct($dados = []){
            $this->dados = $dados;
            $this->HabilidadesRepository = new HabilidadesRepository();
        }

        
        public function setDadosCorpoRequest($dadosRequest){
            $this->dadosCorpoRequest = $dadosRequest;
        }

        public function validarGet(){
            $retorno = null;
            $recurso = $this->dados['recurso'];
            if(in_array($recurso, self::RECURSOS_GET)){
                $retorno = $this->dados['key'] > 0 ? $this->getOneByKey() : $this->$recurso();
            }else{
                throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
            }

            $this->validarRetornoRequest($retorno);

            return $retorno;
        }

        private function getOneByKey(){
            return $this->HabilidadesRepository->getOneByKey($this->dados['key']);
        }

        public function validarDelete(){
            $retorno = null;
            $recurso = $this->dados['recurso'];
            if(in_array($recurso, self::RECURSOS_DELETE)){
                $retorno = $this->validarKeyObrigatoria($recurso);
            }else{
                throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
            }
            
            $this->validarRetornoRequest($retorno);

            return $retorno;
        }

        public function validarPost(){
            $retorno = null;
            $recurso = $this->dados['recurso'];
            if(in_array($recurso, self::RECURSOS_POST)){
                $retorno = $this->$recurso();
            }else{
                throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
            }
            
            $this->validarRetornoRequest($retorno);

            return $retorno;
        }

        public function validarPut(){
            $retorno = null;
            $recurso = $this->dados['recurso'];
            if(in_array($recurso, self::RECURSOS_PUT)){
                $retorno = $this->validarKeyObrigatoria($recurso);
            }else{
                throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
            }
            
            $this->validarRetornoRequest($retorno);

            return $retorno;
        }

        private function listar(){
            return $this->HabilidadesRepository->getMySQL()->getAll(self::TABELA);
        }

        private function deletar(){
            if($this->HabilidadesRepository->deleteSkill($this->dados['key'])){
                $this->HabilidadesRepository->getMySQL()->getDb()->commit();
                return ConstantesGenericasUtil::MSG_DELETADO_SUCESSO;
            }

            $this->HabilidadesRepository->getMySQL()->getDb()->rollBack();
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_NAO_AFETADO);
        }

        private function cadastrar(){
           
            if($this->dadosCorpoRequest['tag'] && $this->dadosCorpoRequest['nome']){
                if (!array_key_exists("porcentagem", $this->dadosCorpoRequest)){
                    $this->dadosCorpoRequest['porcentagem'] = 0;
                }
                if (!array_key_exists("cor", $this->dadosCorpoRequest)){
                    $this->dadosCorpoRequest['cor'] = '#FFFFFF';
                }
                if (!array_key_exists("icone", $this->dadosCorpoRequest)){
                    $this->dadosCorpoRequest['icone'] = 'default.svg';
                }

                if($this->HabilidadesRepository->insertSkill( $this->dadosCorpoRequest) > 0){
                    $idInserido = $this->HabilidadesRepository->getMySQL()->getDb()->lastInsertId();
                    $this->HabilidadesRepository->getMySQL()->getDb()->commit();
                    //return ['id_inserido' => $idInserido];
                    return ['tag_inserida' => $this->dadosCorpoRequest['tag']];
                }

                $this->HabilidadesRepository->getMySQL()->getDb()->rollBack();
                throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
            }
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_TAG_NOME_OBRIGATORIO);
            
        }

        private function atualizar(){
            if($this->HabilidadesRepository->updateSkill($this->dados['key'], $this->dadosCorpoRequest) > 0){
                $this->HabilidadesRepository->getMySQL()->getDb()->commit();
                return ConstantesGenericasUtil::MSG_ATUALIZADO_SUCESSO;
            }

            $this->HabilidadesRepository->getMySQL()->getDb()->rollBack();
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_NAO_AFETADO);
        }

        private function validarRetornoRequest($retorno){
            if($retorno === null){
                throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
            }
        }

        private function validarKeyObrigatoria($recurso){
            if($this->dados['key'] != ""){
                $retorno = $this->$recurso();
            }else{
                throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_TAG_OBRIGATORIA);
            }

            return $retorno;
        }
        
    }