<?php

class Query extends xPDOQuery_mysql {

    public function fetch($asArray = true)
    {
        if (empty($this->query['columns']))
        {
            $this->select($this->xpdo->getSelectColumns($this->getClass()));
        }

        if ( ! $asArray)
        {
            return $this->xpdo->getCollection($this->getClass(), $this);

        }

        if ( ! $this->prepare() or ! $this->stmt->execute())
        {
            return null;
        }

        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
