<?php 

    namespace Repository;

    use DB\MySQL;
    use Util\ConstantesGenericasUtil;
    use InvalidArgumentException;
    
    class ProjetosRepository{

        private object $MySQL;
        public const TABELA = "projetos";
        public const TABELA_TAD_ID = "projetos_has_habilidades";

        public function __construct(){
            $this->MySQL = new MySQL();
        }

        public function getAll(){
            $consulta = 'SELECT P.id, P.nome, P.imagemUrl, 
                P.projetoUrl, P.githubUrl, P.descricao, P.visualizacoes, 
                GROUP_CONCAT(DISTINCT PH.habilidades_tag ) AS tags
                FROM ' . self::TABELA . ' AS P
                INNER JOIN ' . self::TABELA_TAD_ID . ' AS PH  ON P.id = PH.projetos_id
                INNER JOIN projetos_has_habilidades ON PH.projetos_id = PH.projetos_id 
                GROUP BY  P.id';
        
            $stmt = $this->MySQL->getDb()->query($consulta);
            $registros = $stmt->fetchAll($this->MySQL->getDb()::FETCH_ASSOC);
            if (is_array($registros) && count($registros) > 0) {
                return $registros;
            }
            
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_SEM_RETORNO);
        }

      

        public function getOneByKey($id){    
            if ($id) {
                $consulta = 'SELECT P.id, P.nome, P.imagemUrl, 
                P.projetoUrl, P.githubUrl, P.descricao, P.visualizacoes, 
                GROUP_CONCAT(DISTINCT PH.habilidades_tag ) AS tags
                FROM ' . self::TABELA . ' AS P
                INNER JOIN ' . self::TABELA_TAD_ID . ' AS PH  ON P.id = PH.projetos_id
                INNER JOIN projetos_has_habilidades ON PH.projetos_id = PH.projetos_id
                WHERE P.id = :id
                GROUP BY  P.id';
                
                $stmt = $this->MySQL->getDb()->prepare($consulta);
                $stmt->bindParam(':id', $id);
                $stmt->execute();

                $totalRegistros = $stmt->rowCount();
                if ($totalRegistros === 1) {
                    return $stmt->fetch($this->MySQL->getDb()::FETCH_ASSOC);
                }
                throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_SEM_RETORNO);
            }
    
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_ID_OBRIGATORIO);
        }






        public function insertProject($dados){
            $consultaInsert = 'INSERT INTO '. self::TABELA . ' 
            (nome, imagemUrl, projetoUrl, githubUrl, descricao, visualizacoes) VALUES 
            (:nome, :imagem, :projeto, :github, :descricao, :visualizacoes)';
            $this->MySQL->getDb()->beginTransaction();
            $stmt = $this->MySQL->getDb()->prepare($consultaInsert);
            $stmt->bindParam(':nome', $dados['nome']);
            $stmt->bindParam(':imagem', $dados['imagemUrl']);
            $stmt->bindParam(':projeto', $dados['projetoUrl']);
            $stmt->bindParam(':github', $dados['githubUrl']);
            $stmt->bindParam(':descricao', $dados['descricao']);
            $stmt->bindParam(':visualizacoes', $dados['visualizacoes']);
            $stmt->execute();
            return $stmt->rowCount();
        }

        public function updateUser($id, $dados){
            $consultaUpdate = 'UPDATE '. self::TABELA . ' SET login = :login, senha = :senha WHERE id = :id';
            $this->MySQL->getDb()->beginTransaction();
            $stmt = $this->MySQL->getDb()->prepare($consultaUpdate);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':login', $dados['login']);
            $stmt->bindParam(':senha', $dados['senha']);
            $stmt->execute();
            return $stmt->rowCount();
        }

        public function getMySQL(){
            return $this->MySQL;
        }

    }