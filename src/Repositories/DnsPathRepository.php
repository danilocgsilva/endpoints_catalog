<?php

declare(strict_types=1);

namespace Danilocgsilva\EndpointsCatalog\Repositories;

use Danilocgsilva\EndpointsCatalog\Models\DnsPath;
use PDO;
use Danilocgsilva\EndpointsCatalog\Repositories\Interfaces\BaseRepositoryInterface;
use Danilocgsilva\EndpointsCatalog\Models\{Path, Dns};

/**
 * @template-implements BaseRepositoryInterface<DnsPath>
 */
class DnsPathRepository extends AbstractRepository implements BaseRepositoryInterface
{
    public const MODEL = DnsPath::class;

    /**
     * @param \Danilocgsilva\EndpointsCatalog\Models\Dns $dns
     * @param \Danilocgsilva\EndpointsCatalog\Models\Path $path
     * @return void
     */
    public function saveEndpoint(Dns $dns, Path $path): void
    {
        $dnsPath = new DnsPath();
        $dnsPath->setDnsId($dns->id);
        $dnsPath->setPathId($path->id);
        $this->save($dnsPath);
    }
    
    /**
     * @param DnsPath $model
     * @return void
     */
    public function save($model): void
    {
        $this->pdo->prepare(
            sprintf("INSERT INTO %s (path_id, dns_id) VALUES (:path_id, :dns_id)", self::MODEL::TABLENAME)
        )->execute([
            ':path_id' => $model->path_id,
            ':dns_id' => $model->dns_id
        ]);
    }

    /**
     * @param int $id
     * @return \Danilocgsilva\EndpointsCatalog\Models\DnsPath
     */
    public function get(int $id): DnsPath
    {
        $preResults = $this->pdo->prepare(sprintf("SELECT path_id, dns_id FROM %s WHERE id = :id;", self::MODEL::TABLENAME));
        $preResults->setFetchMode(PDO::FETCH_NUM);
        $preResults->execute([':id' => $id]);
        $fetchedData = $preResults->fetch();
        return (new DnsPath())
            ->setPathId($fetchedData[0])
            ->setDnsId($fetchedData[1]);
    }

    /**
     * @param int $id
     * @param DnsPath $model
     * @return void
     */
    public function replace(int $id, $model): void
    {
        $fields = [];
        $params = [':id' => $id];
    
        foreach (['path_id', 'dns_id'] as $field) {
            if (isset($model->$field)) {
                $fields[] = "$field = :$field";
                $params[":$field"] = $model->$field;
            }
        }
    
        if ($fields) {
            $query = sprintf(
                "UPDATE %s SET %s WHERE id = :id;",
                self::MODEL::TABLENAME,
                implode(', ', $fields)
            );
            $this->pdo->prepare($query)->execute($params);
        }
    }

    /**
     * @param int $dnsId
     * @param int $pathId
     * @return \Danilocgsilva\EndpointsCatalog\Models\DnsPath
     */
    public function findByDnsIdAndPathId(int $dnsId, int $pathId): DnsPath
    {
        $query = sprintf("SELECT id, path_id, dns_id FROM %s WHERE path_id = :path_id AND dns_id = :dns_id;", self::MODEL::TABLENAME);
        $preResults = $this->pdo->prepare($query);
        $preResults->execute([
            ':path_id' => $pathId,
            ':dns_id' => $dnsId
        ]);
        $preResults->setFetchMode(PDO::FETCH_ASSOC);
        $row = $preResults->fetch();

        return (new DnsPath())
            ->setId($row['id'])
            ->setDnsId($row['dns_id'])
            ->setPathId($row['path_id']);
    }
    
    /**
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $this->pdo->prepare(
            sprintf("DELETE FROM %s WHERE id = :id", self::MODEL::TABLENAME)
        )->execute([":id" => $id]);
    }

    /**
     * @return array<DnsPath>
     */
    public function list(): array
    {
        $query = sprintf(
            "SELECT %s, %s FROM %s;",
            "path_id",
            "dns_id",
            self::MODEL::TABLENAME
        );

        $preResults = $this->pdo->prepare($query);
        $preResults->setFetchMode(PDO::FETCH_NUM);
        $preResults->execute();
        $dnsPathRepositoryList = [];
        while ($row = $preResults->fetch()) {
            $dnsPathRepositoryList[] = (new DnsPath())->setPathId($row[0])->setDnsId($row[1]);
        }
        return $dnsPathRepositoryList; 
    }
}
