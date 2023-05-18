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
            $tags  = explode(',', $dados['tags']);
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
            if($this->dados['sort']){
                $dados = $this->ProjetosRepository->getByTag($this->dados['sort'][1]);
            }else{
                $dados = $this->ProjetosRepository->getAll();
            }
            
            for($i=0; $i < count($dados); $i++){
                $tags = explode(',', $dados[$i]['tags']);
                $dados[$i]['tags'] = $tags;
            }
            
            return $dados; 
        }

        private function deletar(){
            return $this->ProjetosRepository->getMySQL()->delete(self::TABELA, $this->dados['key']);
        }

        private function cadastrar(){
            if(array_key_exists('nome', $this->dadosCorpoRequest) && array_key_exists('tags', $this->dadosCorpoRequest)){
                if(is_array($this->dadosCorpoRequest['tags']) && count($this->dadosCorpoRequest['tags']) > 0){
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
                }else{
                    throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_TAG_ARRAY);
                }

                if($this->ProjetosRepository->insertProject($this->dadosCorpoRequest) > 0){
                    $idInserido = $this->ProjetosRepository->getMySQL()->getDb()->lastInsertId();
                    $this->ProjetosRepository->getMySQL()->getDb()->commit();
                    foreach ($this->dadosCorpoRequest['tags'] as $tag) {
                        $this->Projetos_has_habilidadesRepository->insert($idInserido, $tag);
                    }      
                }
                
                return ['id_inserido' => $idInserido];
            }
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_NOME_TAGS_OBRIGATORIO);
        }

        private function atualizar(){
            $fields = ['nome', 'imagemUrl', 'projetoUrl', 'githubUrl', 'descricao', 'visualizacoes', 'tags'];

            foreach($fields as $field){
                if(!array_key_exists($field , $this->dadosCorpoRequest)) {
                    throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_PREENCHA_TODOS_CAMPOS);
                }
            }

            if(is_array($this->dadosCorpoRequest['tags']) && count($this->dadosCorpoRequest['tags']) > 0){
                if($this->ProjetosRepository->updateProject($this->dados['key'], $this->dadosCorpoRequest) > 0){
                    $this->ProjetosRepository->getMySQL()->getDb()->commit();
                }

                $lastTags = $this->Projetos_has_habilidadesRepository->getAll($this->dados['key'])[0];
                $lastTags = explode(',', $lastTags['tags']);

                foreach($lastTags as $lastTag){
                    if(!in_array($lastTag, $this->dadosCorpoRequest['tags'])){
                        $this->Projetos_has_habilidadesRepository->delete($this->dados['key'], $lastTag);   
                    }
                }

                foreach($this->dadosCorpoRequest['tags'] as $tag){
                    if(!in_array($tag, $lastTags)){
                        $this->Projetos_has_habilidadesRepository->insert($this->dados['key'], $tag);   
                    }
                }
                return ['id_atualizado' => $this->dados['key']];
            }else{
                throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_TAG_ARRAY);
            }  
        }

        private function validarRetornoRequest($retorno){
            if($retorno === null){
                throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
            }
        }

        private function validarIdObrigatorio($recurso){
            if($this->dados['key'] > 0){
                $retorno = $this->$recurso();
            }else{
                throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_ID_OBRIGATORIO);
            }

            return $retorno;
        }
        
    }