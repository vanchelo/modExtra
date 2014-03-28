<?php

require_once __DIR__ . '/../libraries/Query.php';

abstract class Repository {

    /**
     * @var modX
     */
    protected $modx;
    /**
     * @var modExtra
     */
    protected $modExtra;
    /**
     * @var string
     */
    protected $class;

    function __construct(modX $modx, modExtra $modExtra)
    {
        $this->modx = $modx;
        $this->modExtra = $modExtra;

        $this->initialize();

        if ( ! isset($this->class))
        {
            throw new InvalidArgumentException('Object $class not defined');
        }
    }

    abstract protected function initialize();

    public function published($asArray = true)
    {
        $q = $this->newQuery();
        $q->where([
            'published' => 1
        ]);

        return $q->fetch($asArray);
    }

    public function all($pdo = true, array $columns = [], array $where = [])
    {
        $q = $this->newQuery();

        if ($columns)
        {
            if ( ! in_array('id', $columns))
            {
                $columns[] = 'id';
            }

            $q->select($columns);
        }

        if (isset($where))
        {
            $q->where($where);
        }

        return $q->fetch($pdo);
    }

    public function count($where = null)
    {
        $q = $this->newQuery();
        if (is_array($where))
        {
            $q->where($where);
        }

        return $this->modx->getCount($this->class, $q);
    }

    public function newQuery()
    {
        return new Query($this->modx, $this->class);
    }

    public function get($where)
    {
        return $this->modx->getObject($this->class, ! is_array($where) ? (int) $where : $where);
    }

    public function update($id, Container $data)
    {
        $object = $this->get($id);

        if ( ! $object)
        {
            return null;
        }

        $object->fromArray($data->getProperties());

        return $object->save() ? $object : null;
    }


    public function create(Container $data)
    {
        $object = $this->modx->newObject($this->class);

        $object->fromArray($data->getProperties());

        return $object->save() ? $object : null;
    }

    public function delete($id)
    {
        if ($object = $this->get($id))
        {
            return $object->remove();
        }

        return false;
    }

}
