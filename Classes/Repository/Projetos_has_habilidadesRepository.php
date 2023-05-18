<?php
    namespace Repository;

    use DB\MySQL;
    use Util\ConstantesGenericasUtil;
    use InvalidArgumentException;

    class Projetos_has_habilidadesRepository{

        private object $MySQL;
        public const TABELA = "projetos_has_habilidades";

        public function __construct(){
            $this->MySQL = new MySQL();
        }

        public function insert($id, $tag){
            $consultaInsert = 'INSERT INTO '. self::TABELA .' (projetos_id, habilidades_tag) VALUES (:id, :tag)';
            $this->MySQL->getDb()->beginTransaction();
            $stmt = $this->MySQL->getDb()->prepare($consultaInsert);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':tag', $tag);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $this->MySQL->getDb()->commit();
                return ConstantesGenericasUtil::MSG_INSERIDO_SUCESSO;
            }
            $this->MySQL->getDb()->rollBack();
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_SEM_RETORNO);
            
           
        }

        public function getAll($id){
            $consulta = 'SELECT GROUP_CONCAT(DISTINCT habilidades_tag ) as tags FROM '. self::TABELA .' WHERE projetos_id = :id';
            $stmt = $this->MySQL->getDb()->prepare($consulta);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $registros = $stmt->fetchAll($this->MySQL->getDb()::FETCH_ASSOC);
            return $registros;
        
    }

        public function delete($id, $tag){
            $consultaDelete = 'DELETE FROM '. self::TABELA .' WHERE projetos_id = :id AND habilidades_tag = :tag';
            $this->MySQL->getDb()->beginTransaction();
            $stmt = $this->MySQL->getDb()->prepare($consultaDelete);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':tag', $tag);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $this->MySQL->getDb()->commit();
                return ConstantesGenericasUtil::MSG_DELETADO_SUCESSO;
            }
            $this->MySQL->getDb()->rollBack();
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_SEM_RETORNO);
            
 
        }
        
        public function getMySQL(){
            return $this->MySQL;
        }

    }