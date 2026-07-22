<?php

namespace App\Repository;

use App\Entity\Transaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transaction>
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    public function save(Transaction $t, bool $flush = true): void
    {
        $this->getEntityManager()->persist($t);
        if ($flush) { $this->getEntityManager()->flush(); }
    }

    public function remove(Transaction $t, bool $flush = true): void
    {
        $this->getEntityManager()->remove($t);
        if ($flush) { $this->getEntityManager()->flush(); }
    }

    /** Soma por direção (entrada/saída) em um período. */
    public function totalByDirection(string $direction, \DateTimeImmutable $from, \DateTimeImmutable $to): float
    {
        return (float) ($this->createQueryBuilder('t')
            ->select('COALESCE(SUM(t.amount), 0)')
            ->where('t.direction = :d')->setParameter('d', $direction)
            ->andWhere('t.occurredAt BETWEEN :f AND :to')
            ->setParameter('f', $from)->setParameter('to', $to)
            ->getQuery()->getSingleScalarResult());
    }

    /** Total arrecadado por tipo (dízimo, oferta...) no período. */
    public function totalByKind(string $kind, \DateTimeImmutable $from, \DateTimeImmutable $to): float
    {
        return (float) ($this->createQueryBuilder('t')
            ->select('COALESCE(SUM(t.amount), 0)')
            ->where('t.kind = :k')->setParameter('k', $kind)
            ->andWhere('t.occurredAt BETWEEN :f AND :to')
            ->setParameter('f', $from)->setParameter('to', $to)
            ->getQuery()->getSingleScalarResult());
    }

    /** Fluxo de caixa mensal (12 meses) para gráficos e prestação de contas. */
    public function monthlyCashFlow(int $year): array
    {
        $rows = $this->createQueryBuilder('t')
            ->select("MONTH(t.occurredAt) AS m, t.direction AS dir, SUM(t.amount) AS total")
            ->where('YEAR(t.occurredAt) = :y')->setParameter('y', $year)
            ->groupBy('m')->addGroupBy('dir')
            ->getQuery()->getArrayResult();

        $out = [];
        for ($i = 1; $i <= 12; $i++) {
            $out[$i] = ['entrada' => 0.0, 'saida' => 0.0];
        }
        foreach ($rows as $r) {
            $out[(int) $r['m']][$r['dir']] = (float) $r['total'];
        }
        return $out;
    }
}
