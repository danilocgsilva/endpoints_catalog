<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Repositories;

use Danilocgsilva\EndpointsCatalog\Models\Platform;
use PDO;
use Danilocgsilva\EndpointsCatalog\Repositories\Interfaces\BaseRepositoryInterface;

/**
 * @template-implements BaseRepositoryInterface<Path>
 */
class PlatformRepository extends AbstractRepository implements BaseRepositoryInterface
{
    public const MODEL = Platform::class;

    /**
     * @param Platform $platformModel
     * @return void
     */
    public function save($platformModel): void
    {
        $fieldsSection = "name";
        $valuesSection = ":name";
        $placeholdersArray = [
            ':name' => $platformModel->name
        ];

        if (isset($platformModel->description)) {
            $fieldsSection .= ", description";
            $valuesSection .= ", :description";
            $placeholdersArray[":description"] = $platformModel->description;
        }
        
        $this->pdo->prepare(
            sprintf(
                "INSERT INTO %s (%s) VALUES (%s)",
                $fieldsSection,
                self::MODEL::TABLENAME,
                $valuesSection
            )
        )->execute($placeholdersArray);
    }

    /**
     * @param Platform $platformModel
     * @return void
     */
    public function saveAndAssingId($platformModel): void
    {
        $this->save($platformModel);
        $platformModel->setId(
            (int) $this->pdo->lastInsertId()
        );
    }

    /**
     * @param int $id
     * @return \Danilocgsilva\EndpointsCatalog\Models\Platform
     */
    public function get(int $id): Platform
    {
        $preResults = $this->pdo->prepare(
            sprintf("SELECT `id`, `name`, `description` FROM %s WHERE id = :id;", self::MODEL::TABLENAME)
        );
        $preResults->setFetchMode(PDO::FETCH_ASSOC);
        $preResults->execute([':id' => $id]);
        $fetchedData = $preResults->fetch();

        // $id, $name and $description, same fields from query
        extract($fetchedData);

        $platform = (new Platform())->setId($id)->setName($name);
        if (isset($description)) {
            $platform->setDescription($description);
        }

        return $platform;
    }

    /**
     * @param int $id
     * @param Platform $platformModel
     * @return void
     */
    public function replace(int $id, $platformModel): void
    {
        $setSection = "name = :name";
        $placeholdersArray = [
            ':name' => $platformModel->name,
            ':id' => $id
        ];
        if (isset($platformModel->description)) {
            $setSection .= ", description = :description";
            $placeholdersArray[":description"] = $platformModel->description;
        }
        
        $query = sprintf(
            "UPDATE %s SET %s WHERE id = :id;",
            self::MODEL::TABLENAME,
            $setSection
        );

        $this->pdo->prepare($query)->execute($placeholdersArray);
    }

    public function delete(int $id): void
    {
        $this->pdo->prepare(
            sprintf("DELETE FROM %s WHERE id = :id", self::MODEL::TABLENAME)
        )->execute([':id' => $id]);
    }

    public function list(): array
    {
        $query = sprintf(
            "SELECT id, %s FROM %s;",
            "name, description",
            self::MODEL::TABLENAME
        );

        $preResults = $this->pdo->prepare($query);
        $preResults->setFetchMode(PDO::FETCH_ASSOC);
        $preResults->execute();
        $dnsRepositoryList = [];
        while ($row = $preResults->fetch()) {
            // $id, $name and $description, same fields from query.
            extract($row);
            
            $platform = (new Platform())->setId($id)->setName($name);
            if (isset($description)) {
                $platform->setDescription($description);
            }
            $dnsRepositoryList[] = $platform;
        }
        return $dnsRepositoryList; 
    }
}
