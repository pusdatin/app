<?php

/**
 * Linna.
 *
 * This work would be a little PHP framework, a learn exercice. 
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2015, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 * @version 0.1.0
 */
namespace App\Mappers;

use Linna\Database\DomainObjectInterface;
use Linna\Database\MapperAbstract;
use Linna\Database\Database;
use App\DomainObjects\User;

/**
 * UserMapper.
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 */
class UserMapper extends MapperAbstract
{
    /**
     * @var object Database Connection
     */
    protected $dBase;

    /**
     * UserMapper Constructor.
     * 
     * Open only a database connection
     *
     */
    public function __construct()
    {
        $this->dBase = Database::connect();
    }

    /**
     * Fetch a user object by id
     * 
     * @param string $userId
     *
     * @return User
     */
    public function findById($userId)
    {
        $pdos = $this->dBase->prepare('SELECT user_id AS _Id, name, description, password, active, created, last_update FROM user WHERE user_id = :id');

        $pdos->bindParam(':id', $userId, \PDO::PARAM_INT);
        $pdos->execute();

        return $pdos->fetchObject('\App\DomainObjects\User');
    }

    /**
     * Fetch a user object by name
     * 
     * @param string $name
     *
     * @return User
     */
    public function findByName($name)
    {
        $pdos = $this->dBase->prepare('SELECT user_id AS _Id, name, description, password, active, created, last_update FROM user WHERE md5(name) = :name');

        $hashedUserName = md5($name);

        $pdos->bindParam(':name', $hashedUserName, \PDO::PARAM_STR);
        $pdos->execute();

        return $pdos->fetchObject('\App\DomainObjects\User');
    }

    public function getAllUsers()
    {
        $pdos = $this->dBase->prepare('SELECT user_id as _Id, name, description, password, active, created, last_update FROM user ORDER BY name ASC');

        $pdos->execute();

        return $pdos->fetchAll(\PDO::FETCH_CLASS, '\App\DomainObjects\User');
    }

    
    /**
     * _create.
     * 
     * Create a new User DomainObject
     *
     * @return User
     *
     * @since 0.1.0
     */
    protected function _create()
    {
        return new User();
    }

    /**
     * _insert.
     * 
     * Insert the DomainObject in persistent storage
     * 
     * This may include connecting to the database
     * and running an insert statement.
     *
     * @param DomainObjectInterface $user
     *
     * @since 0.1.0
     */
    protected function _insert(DomainObjectInterface $user)
    {
        $pdos = $this->dBase->prepare('INSERT INTO user (name, description, password, created) VALUES (:name, :description, :password, NOW())');

        $pdos->bindParam(':name', $user->name, \PDO::PARAM_STR);
        $pdos->bindParam(':description', $user->description, \PDO::PARAM_STR);
        $pdos->bindParam(':password', $user->password, \PDO::PARAM_STR);
        $pdos->execute();

        return $this->dBase->lastInsertId();
    }

    /**
     * Update the DomainObject in persistent storage
     * 
     * This may include connecting to the database
     * and running an update statement.
     *
     * @param DomainObjectInterface $obj
     *
     */
    protected function _update(DomainObjectInterface $obj)
    {
        $pdos = $this->dBase->prepare('UPDATE user SET name = :name, description = :description,  password = :password, active = :active WHERE user_id = :user_id');

        $objId = $obj->getId();

        $pdos->bindParam(':user_id', $objId, \PDO::PARAM_INT);

        $pdos->bindParam(':name', $obj->name, \PDO::PARAM_STR);
        $pdos->bindParam(':password', $obj->password, \PDO::PARAM_STR);
        $pdos->bindParam(':description', $obj->description, \PDO::PARAM_STR);
        $pdos->bindParam(':active', $obj->active, \PDO::PARAM_INT);

        $pdos->execute();
    }

    /**
     * __delete.
     * 
     * Delete the DomainObject from persistent storage
     * 
     * This may include connecting to the database
     * and running a delete statement.
     *
     * @param DomainObjectAbstract $obj
     *
     * @since 0.1.0
     */
    protected function _delete(DomainObjectInterface $obj)
    {
        $pdos = $this->dBase->prepare('DELETE FROM user WHERE user_id = :user_id');

        $pdos->bindParam(':user_id', $obj->getId(), \PDO::PARAM_INT);

        $pdos->execute();
    }
}
