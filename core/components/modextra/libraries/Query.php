<?php

class Query extends xPDOQuery_mysql {

    public function fetch($pdo = true, $fetch_style = PDO::FETCH_ASSOC)
    {
        if (empty($this->query['columns']))
        {
            $this->select($this->xpdo->getSelectColumns($this->getClass()));
        }

        $this->xpdo->executedQueries++;

        if ( ! $pdo)
        {
            return $this->xpdo->getCollection($this->getClass(), $this);

        }

        if ( ! $this->prepare() or ! $this->stmt->execute())
        {
            return null;
        }

        return $this->stmt->fetchAll($fetch_style);
    }

}
