<?php

class Application_Model_PessoaJuridica extends Application_Model_Pessoa
{

    private $table = null;

    public function getTable()
    {
        if (is_null($this->table)) {
            $this->table = new Application_Model_DbTable_PessoaJuridica();
        }
        return $this->table;
    }

    public function select($where = array(), $order = null, $limit = null)
    {
        $select = $this->getTable()->select()->order($order)->limit($limit);

        foreach ($where as $coluna => $valor) :
            $select->where($coluna, $valor);
        endforeach;

        return $this->getTable()->fetchAll($select)->toArray();
    }

    public function buscarPessoaJuridica($where = array(), $order = null, $limit = null)
    {
        $subselect = $this->getTable()->select()
            ->setIntegrityCheck(false)
            ->from(array('h' => 'CORPORATIVO.H_SITUACAO_CADASTRAL_PJ'), array('h.ID_PESSOA_JURIDICA', new Zend_Db_Expr("MAX(h.DT_SITUACAO_CADASTRAL) AS ultimaData")))
            ->group(array('h.ID_PESSOA_JURIDICA'));

        $select = $this->getTable()->select();
        $select->setIntegrityCheck(false);
        $select->from(array('p' => 'CORPORATIVO.S_PESSOA_JURIDICA'), array('p.ID_PESSOA_JURIDICA',
            'p.ID_TIPO_LUCRO',
            'p.NR_CNPJ',
            'p.NR_INSCRICAO_ESTADUAL',
            'p.NM_RAZAO_SOCIAL',
            'p.NM_FANTASIA',
            'p.NR_CEI'));

        $select->joinLeft(array('nj' => 'CORPORATIVO.S_NATUREZA_JURIDICA'),
            'p.CD_NATUREZA_JURIDICA = nj.CD_NATUREZA_JURIDICA',
            array('nj.CD_NATUREZA_JURIDICA', 'nj.DS_NATUREZA_JURIDICA')
        );

        $select->joinLeft(array('hspj' => new Zend_Db_Expr("({$subselect})")),
            'hspj.ID_PESSOA_JURIDICA = p.ID_PESSOA_JURIDICA',
            array('hspj.ID_PESSOA_JURIDICA', 'hspj.ultimaData')
        );

        $select->joinLeft(array('sitpj' => 'CORPORATIVO.H_SITUACAO_CADASTRAL_PJ'),
            'sitpj.ID_PESSOA_JURIDICA = hspj.ID_PESSOA_JURIDICA 
            AND sitpj.DT_SITUACAO_CADASTRAL = hspj.ultimaData',
            array('sitpj.ID_PESSOA_JURIDICA', 'sitpj.CD_SITUACAO_CADASTRAL')
        );

        $select->joinLeft(array('sit' => 'CORPORATIVO.S_TIPO_SITUACAO_CADASTRAL_PJ'),
            'sit.CD_SITUACAO_CADASTRAL = sitpj.CD_SITUACAO_CADASTRAL',
            array('sit.DS_SITUACAO_CADASTRAL')
        );

        if ($where) {
            foreach ($where as $coluna => $valor) :
                $select->where($coluna, $valor);
            endforeach;
        }

        $select->order('p.NM_RAZAO_SOCIAL');
        $select->order('p.NM_FANTASIA');
        $select->order($order);
        $select->limit($limit);

//        xd($select->assemble());

        return $this->getTable()->fetchAll($select);
    }

    public function find($id)
    {
        return $this->getTable()->find($id)->current();
    }

    public function insert(array $request)
    {
        return $this->getTable()->createRow()->setFromArray($request)->save();
    }

    public function update(array $request, $id)
    {
        if (is_array($id)) {
            $where = $id;
        } else {
            $where["ID_PESSOA_JURIDICA = ?"] = $id;
        }
        return $this->getTable()->update($request, $where);
    }

    public function delete($id)
    {
        return $this->getTable()->find($id)->current()->delete();
    }

    public function consultarReceitaFederal($cpfCnpj, $forcar = null)
    {
        if (14 == strlen($cpfCnpj) && !validaCNPJ($cpfCnpj)) {
            throw new InvalidArgumentException("CNPJ inv�lido");
        }
        $montarUrl = "pessoa_juridica/consultar/" . $cpfCnpj;
        return parent::consultarReceitaFederal($montarUrl, $forcar);
    }

}
