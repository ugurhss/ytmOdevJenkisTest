<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{/* The `<?php` tag is used to indicate the start of a PHP code block in a PHP file. It is necessary to
include this tag at the beginning of a PHP file to inform the server that the content within the
file should be interpreted as PHP code. */

    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $record = $this->find($id);
        $record->update($data);
        return $record;
    }

    public function delete(int $id)
    {
        $record = $this->find($id);
        return $record->delete();
    }

    // foreign key ile veri çekme
    public function getByForeignKey(string $key, int $value)
    {
        return $this->model->where($key, $value)->get();
    }
}

?>
