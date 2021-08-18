<?php

namespace App\Entity;

use \App\Db\Database;
use \PDO;

class Vaga
{

    /**
     * Identificador único
     * @var integer
     */
    public $id;

    /**
     * Titulo da vaga
     * @var string
     */
    public $titulo;

    /**
     * Descricao da vaga
     * @var string
     */
    public $descricao;

    /**
     * Define a vaga ativa
     * @var string(s/n)
     */
    public $ativo;

    /**
     * Data de publicação
     * @var string
     */
    public $data;

    /**
     *  Método para cadastrar uma nova vaga no banco de dados
     *  @return boolean
     */
    public function cadastrar()
    {
        $this->data = date('Y-m-d H:i:s');

        //INSERIR A VAGA NO BANCO
        $obDatabase = new Database('vagas');
        $this->id = $obDatabase->insert([
            'titulo'    => $this->titulo,
            'descricao' => $this->descricao,
            'ativo'     => $this->ativo,
            'data'      => $this->data
        ]);
        return true;
    }
    /**
     * Método responsavel por atualizar as informações no banco de dados
     * @return boolean
     */

    public function atualizar(){
        return (new Database('vagas'))->update('id = '. $this->id, [
                                                                        'titulo'    => $this->titulo,
                                                                        'descricao' => $this->descricao,
                                                                        'ativo'     => $this->ativo,
                                                                        'data'      => $this->data
                                                                    ]);
    }

    /**
     * Método responsavel por excluir a vaga do banco
     * @return boolean
     */
    public function excluir(){
        return (new Database('vagas'))->delete('id = '. $this->id);
    }

    /**
     * Método responsavel por resgatar as vagas no banco de dados
     * @param string $where
     * @param string $order
     * @param string $limit
     * @return array
     */
    public static function getVagas($where = null, $order = null, $limit = null){
        return (new Database('vagas'))->select($where, $order, $limit)
                                      ->fetchAll(PDO::FETCH_CLASS,self::class);
    }

    /**
     * Método responsavel por resgatar uma vaga com base no id
     * @param integer $id
     * @return Vaga
     */
    public static function getVaga($id){
        return (new Database('vagas'))->select('id = '. $id)
                                      ->fetchObject(self::class);
                                      
    }
}
