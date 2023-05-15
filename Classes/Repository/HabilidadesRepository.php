<?php 

    namespace Repository;

    use Util\ConstantesGenericasUtil;
    use InvalidArgumentException;
    use DB\MySQL;

    class HabilidadesRepository{

        private object $MySQL;
        public const TABELA = "habilidades";

        public function __construct(){
            $this->MySQL = new MySQL();
        }

        public function getOneByKey($tag){      
        if ($tag) {
            $consulta = 'SELECT * FROM ' . self::TABELA . ' WHERE tag = :tag';
            $stmt = $this->MySQL->getDb()->prepare($consulta);
            $stmt->bindParam(':tag', $tag);
            $stmt->execute();
            $totalRegistros = $stmt->rowCount();
            if ($totalRegistros === 1) {
                return $stmt->fetch($this->MySQL->getDb()::FETCH_ASSOC);
            }
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_SEM_RETORNO);
        }

        throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_TAG_OBRIGATORIA);
    }

        public function inserSkill($dados){
            $consultaInsert = 'INSERT INTO '. self::TABELA . ' (tag, nome, porcentagem, cor, icone) VALUES (:tag, :nome, :porcentagem, :cor, :icone)';
            $this->MySQL->getDb()->beginTransaction();
            $stmt = $this->MySQL->getDb()->prepare($consultaInsert);
            $stmt->bindParam(':tag', $dados['tag']);
            $stmt->bindParam(':nome', $dados['nome']);
            $stmt->bindParam(':porcentagem', $dados['porcentagem']);
            $stmt->bindParam(':cor', $dados['cor']);
            $stmt->bindParam(':icone', $dados['icone']);
            $stmt->execute();
            return $stmt->rowCount();
        }

        public function updateSkill($tag, $dados){
            $consultaUpdate = 'UPDATE '. self::TABELA . ' SET nome = :nome, porcentagem = :porcentagem, cor = :cor, icone = :icone WHERE tag = :tag';
            $this->MySQL->getDb()->beginTransaction();
            $stmt = $this->MySQL->getDb()->prepare($consultaUpdate);
            $stmt->bindParam(':tag', $tag);
            $stmt->bindParam(':nome', $dados['nome']);
            $stmt->bindParam(':porcentagem', $dados['porcentagem']);
            $stmt->bindParam(':cor', $dados['cor']);
            $stmt->bindParam(':icone', $dados['icone']);
            $stmt->execute();
            return $stmt->rowCount();
        }

        public function getMySQL(){
            return $this->MySQL;
        }

        public function deleteSkill($tag)
        {
            $consultaDelete = 'DELETE FROM ' . self::TABELA . ' WHERE tag = :tag';
            $this->MySQL->getDb()->beginTransaction();
            $stmt = $this->MySQL->getDb()->prepare($consultaDelete);
            $stmt->bindParam(':tag', $tag);
            $stmt->execute();
            return $stmt->rowCount();
        }

    }