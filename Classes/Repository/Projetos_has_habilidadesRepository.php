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

        public function insertSkillinProject($id, $tag){
            
            $consultaInsert = 'INSERT INTO '. self::TABELA . ' (projetos_id, habilidades_tag) VALUES (:id, :tag)';
            $this->MySQL->getDb()->beginTransaction();
            $stmt = $this->MySQL->getDb()->prepare($consultaInsert);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':tag', $tag);
            $stmt->execute();
            return $stmt->rowCount();
        }

        public function updateSkillinProject($id, $tag){
           
        }

       

        public function getMySQL(){
            return $this->MySQL;
        }

    }