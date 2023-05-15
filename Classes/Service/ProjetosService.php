<?php

    namespace Service;

    use Repository\ProjetosRepository;
    use Repository\Projetos_has_habilidadesRepository;
    use Util\ConstantesGenericasUtil;
    use InvalidArgumentException;
  
    class ProjetosService{
        public const    TABELA = 'projetos';
        public const    RECURSOS_GET = ['listar'];
        public const    RECURSOS_DELETE = ['deletar'];
        public const    RECURSOS_POST = ['cadastrar'];
        public const    RECURSOS_PUT = ['atualizar'];
        
        private array $dados;
        private array $dadosCorpoRequest = [];

        private object $ProjetosRepository;

        public function __construct($dados = []){
            $this->dados = $dados;
            $this->ProjetosRepository = new ProjetosRepository();
            $this->Projetos_has_habilidadesRepository = new Projetos_has_habilidadesRepository();
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
            $dados = $this->ProjetosRepository->getOneByKey($this->dados['key']);
            $tags  = explode(',', $dados['tags'], 2);
            $dados['tags'] = $tags;  
            return $dados; 
        }

        public function validarDelete(){
            $retorno = null;
            $recurso = $this->dados['recurso'];
            if(in_array($recurso, self::RECURSOS_DELETE)){
                $retorno = $this->validarIdObrigatorio($recurso);
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
                $retorno = $this->validarIdObrigatorio($recurso);
            }else{
                throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
            }
            
            $this->validarRetornoRequest($retorno);

            return $retorno;
        }

        public function setDadosCorpoRequest($dadosRequest){
            $this->dadosCorpoRequest = $dadosRequest;
        }

        private function listar(){
            $dados = $this->ProjetosRepository->getAll();
            for($i=0; $i < count($dados); $i++){
                $tags = explode(',', $dados[$i]['tags']);
                $dados[$i]['tags'] = $tags;
            }
            
            return $dados; 
        }

        private function deletar(){
            return $this->ProjetosRepository->getMySQL()->delete(self::TABELA, $this->dados['id']);
        }

        private function cadastrar(){
            if($this->dadosCorpoRequest['nome']){
                if (!array_key_exists("imagemUrl", $this->dadosCorpoRequest)){
                    $this->dadosCorpoRequest['imagemUrl'] = 'www.url/imagedefault';
                }
                if (!array_key_exists("projetoUrl", $this->dadosCorpoRequest)){
                    $this->dadosCorpoRequest['projetoUrl'] = '';
                }
                if (!array_key_exists("githubUrl", $this->dadosCorpoRequest)){
                    $this->dadosCorpoRequest['githubUrl'] = '';
                }
                if (!array_key_exists("descricao", $this->dadosCorpoRequest)){
                    $this->dadosCorpoRequest['descricao'] = '';
                }
                if (!array_key_exists("visualizacoes", $this->dadosCorpoRequest)){
                    $this->dadosCorpoRequest['visualizacoes'] = 0;
                }

                if($this->ProjetosRepository->insertProject($this->dadosCorpoRequest) > 0){
                    $idInserido = $this->ProjetosRepository->getMySQL()->getDb()->lastInsertId();
                    $this->ProjetosRepository->getMySQL()->getDb()->commit();
                }else{
                    $this->ProjetosRepository->getMySQL()->getDb()->rollBack();
                    throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
                }

                if (array_key_exists("tags", $this->dadosCorpoRequest)){
                    if(is_array($this->dadosCorpoRequest['tags'])){
                        foreach ($this->dadosCorpoRequest['tags'] as $tag) {
                            if($this->Projetos_has_habilidadesRepository->insertSkillinProject($idInserido, $tag) > 0){
                                $this->Projetos_has_habilidadesRepository->getMySQL()->getDb()->commit();
                            }else{
                                $this->Projetos_has_habilidadesRepository->getMySQL()->getDb()->rollBack();
                                throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
                            }  
                        }    
                    return ['id_inserido' => $idInserido];
                    }else{
                        throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_TAG_ARRAY);
                    }
                }
                return ['id_inserido' => $idInserido];
            }
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_NOME_OBRIGATORIO);
        }

        private function atualizar(){
            if($this->ProjetosRepository->updateUser($this->dados['id'], $this->dadosCorpoRequest) > 0){
                $this->ProjetosRepository->getMySQL()->getDb()->commit();
                return ConstantesGenericasUtil::MSG_ATUALIZADO_SUCESSO;
            }

            $this->ProjetosRepository->getMySQL()->getDb()->rollBack();
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_NAO_AFETADO);
        }

        private function validarRetornoRequest($retorno){
            if($retorno === null){
                throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
            }
        }

        private function validarIdObrigatorio($recurso){
            if($this->dados['id'] > 0){
                $retorno = $this->$recurso();
            }else{
                throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_ID_OBRIGATORIO);
            }

            return $retorno;
        }
        
    }