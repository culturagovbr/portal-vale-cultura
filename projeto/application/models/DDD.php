<?php

class Application_Model_DDD
{

    private $table = null;

    public function getTable()
    {
        if (is_null($this->table)) {
            $this->table = new Application_Model_DbTable_DDD();
        }
        return $this->table;
    }

    public function select($where = array(), $order = null, $limit = null)
    {
        $select = $this->getTable()->select()->order($order)->limit($limit);

        foreach ($where as $coluna => $valor) :
            $select->where($coluna, $valor);
        endforeach;

//        xd($select->assemble());
        return $this->getTable()->fetchAll($select)->toArray();
    }

    public function find($id)
    {
        return $this->getTable()->find($id)->current();
    }


}

?>