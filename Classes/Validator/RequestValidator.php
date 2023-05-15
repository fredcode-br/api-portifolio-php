<?php
    namespace Validator;

    use Util\ConstantesGenericasUtil;
    use Util\JsonUtil;
    use Repository\TokensAutorizadosRepository;
    use Service\HabilidadesService;
    use Service\ProjetosService;
    use InvalidArgumentException;

    class RequestValidator{
        private $request;
        private $dadosRequest = [];
        private object $TokensAutorizadosRepository;

        const GET = 'get';
        const DELETE = 'delete';
        const PUT = 'put';
        const POST = 'post';
        const HABILIDADES = 'HABILIDADES';
        const PROJETOS = 'PROJETOS';
    



        public function __construct($request){
            $this->request = $request;
            $this->TokensAutorizadosRepository = new TokensAutorizadosRepository();
        }

        public function processarRequest(){
            $retorno = utf8_encode(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA);

            if(in_array($this->request['metodo'], ConstantesGenericasUtil::TIPO_REQUEST, true)){
                $retorno = $this->direcionarRequest();
            }
            
            return $retorno;
        }

        private function direcionarRequest(){
            if($this->request['metodo'] !== self::GET && $this->request['metodo'] !== self::DELETE){
                $this->dadosRequest = JsonUtil::tratarCorpoRequisicaoJson();
            }
            $this->TokensAutorizadosRepository->validarToken(getallheaders()['Authorization']);

            $metodo = $this->request['metodo'];
            return $this->$metodo();
        }   

        private function get(){
            $retorno = utf8_encode(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA);
            if(in_array($this->request['rota'], ConstantesGenericasUtil::TIPO_GET)){
                switch($this->request['rota']){
                    case self::HABILIDADES:
                        $HabilidadesService = new HabilidadesService($this->request);
                        $retorno = $HabilidadesService->validarGet();
                        break;

                    case self::PROJETOS:
                        $ProjetosService = new ProjetosService($this->request);
                        $retorno = $ProjetosService->validarGet();
                        break;

                    default:
                        throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
                }
            }

            return $retorno;
        }

        private function delete(){
            $retorno = utf8_encode(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA);
            if(in_array($this->request['rota'], ConstantesGenericasUtil::TIPO_DELETE)){
                switch($this->request['rota']){
                    case self::HABILIDADES:
                        $HabilidadesService = new HabilidadesService($this->request);
                        $retorno = $HabilidadesService->validarDelete();
                        break;
                    
                    case self::PROJETOS:
                        $ProjetosService = new ProjetosService($this->request);
                        $retorno = $ProjetosService->validarDelete();
                        break;
                    
                    default:
                        throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
                }
            }

            return $retorno;
        }

        private function post(){
            $retorno = utf8_encode(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA);
            if(in_array($this->request['rota'], ConstantesGenericasUtil::TIPO_POST, true)){
                switch($this->request['rota']){
                    case self::HABILIDADES:
                        $HabilidadesService = new HabilidadesService($this->request);
                        $HabilidadesService->setDadosCorpoRequest($this->dadosRequest);
                        $retorno = $HabilidadesService->validarPost();
                        break;

                    case self::PROJETOS:
                        $ProjetosService = new ProjetosService($this->request);
                        $ProjetosService->setDadosCorpoRequest($this->dadosRequest);
                        $retorno = $ProjetosService->validarPost();
                        break;

                    default:
                        throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
                }
            }

            return $retorno;
        }

        private function put(){
            $retorno = utf8_encode(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA);
            if(in_array($this->request['rota'], ConstantesGenericasUtil::TIPO_PUT)){
                switch($this->request['rota']){
                    case self::HABILIDADES:
                        $HabilidadesService = new HabilidadesService($this->request);
                        $HabilidadesService->setDadosCorpoRequest($this->dadosRequest);
                        $retorno = $HabilidadesService->validarPut();
                        break;
                        
                    case self::PROJETOS:
                        $ProjetosService = new ProjetosService($this->request);
                        $ProjetosService->setDadosCorpoRequest($this->dadosRequest);
                        $retorno = $ProjetosService->validarPut();
                        break;

                    default:
                        throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
                }
            }

            return $retorno;
        }
    }