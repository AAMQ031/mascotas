<?php



	namespace Post\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

class PostTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function getPost($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }

    public function savePost(Post $post)
    {
        $data = [
            'nombre' => $post->nombre,
            'raza'  => $post->raza,
            'sexo'  => $post->sexo,
            'fecha_de_nacimiento'  => $post->fecha_de_nacimiento,
            'caracteristicas'  => $post->caracteristicas,
        ];

        $id = (int) $post->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getPost($id);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update post with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deletePost($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}
	


?>