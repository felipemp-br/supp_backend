<?php

/** @noinspection PhpUnused */
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Integracao\Datalake\Conectors;

// use Exception;
use SuppCore\AdministrativoBackend\Helpers\SuppParameterBag;

// use RdKafka\Conf;
// use RdKafka\KafkaConsumer;
// use SuppCore\AdministrativoBackend\Integracao\Datalake\KafkaConectorInterface;

/**
 * src/Integracao/Datalake/Conectors/KafkaConectorCurl.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class KafkaNativeConector /* implements KafkaConectorInterface */
{
    public function __construct(private readonly SuppParameterBag $parameterBag)
    {
    }

//    /**
//     * @throws Exception
//     */
//    public function consumeTopic(string $name): ?array {
//        /** @noinspection PhpAssignmentInConditionInspection */
//        return ($t = $this->consumeTopics([$name])) ? $t['data'] : null;
//    }
//
//    /**
//     * @throws Exception
//     */
//    public function consumeTopics(array $names): array {
//        if(!$this->parameterBag->get('supp_core.administrativo_backend.datalake.kafka.enabled')) {
//            throw new Exception('Serviço de tópicos Kafka desabilitado neste ambiente.');
//        }
//
//        if(!$this->parameterBag->has('supp_core.administrativo_backend.datalake.kafka.config')) {
//            throw new Exception('Serviço de tópicos Kafka habilitado mas não configurado neste ambiente.');
//        }
//
//        /** @noinspection PhpUsageOfSilenceOperatorInspection */
//        @[
//            'server'    => $server,
//            'username'  => $username,
//            'password'  => $password,
//            'group'     => $group
//        ] = $this->parameterBag->get('supp_core.administrativo_backend.datalake.kafka.config');
//        $conf = new Conf();
//        $conf->set('sasl.username', $username);
//        $conf->set('sasl.password', $password);
//        $conf->set('security.protocol', 'sasl_ssl');
//        $conf->set('sasl.mechanism', 'PLAIN');
//        $conf->set('receive.message.max.bytes', '1213486160');
// //        $conf->set('log_level', (string) LOG_DEBUG);
// //        $conf->set('debug', 'all');
//
//        // Set a rebalance callback to log partition assignments (optional)
//        $conf->setRebalanceCb(function (KafkaConsumer $kafka, $err, array $partitions = null) {
//            switch ($err) {
//                case RD_KAFKA_RESP_ERR__ASSIGN_PARTITIONS:
//                    // echo "Assign: ";
//                    // var_dump($partitions);
//                    $kafka->assign($partitions);
//                    break;
//
//                case RD_KAFKA_RESP_ERR__REVOKE_PARTITIONS:
//                    // echo "Revoke: ";
//                    // var_dump($partitions);
//                    $kafka->assign();
//                    break;
//
//                default:
//                    throw new Exception($err);
//            }
//        });
//
//        // Configure the group.id. All consumer with the same group.id will consume
//        // different partitions.
//        $conf->set('group.id', $group);
//
//        // Initial list of Kafka brokers
//        $conf->set('metadata.broker.list', $server);
//
//        // Set where to start consuming messages when there is no initial offset in
//        // offset store or the desired offset is out of range.
//        // 'earliest': start from the beginning
//        $conf->set('auto.offset.reset', 'earliest');
//
//        // Emit EOF event when reaching the end of a partition
//        $conf->set('enable.partition.eof', 'true');
//
//        $consumer = new KafkaConsumer($conf);
//        $consumer->subscribe($names);
//
//        $message = $consumer->consume(1000);
//
//        return match ($message->err) {
//            RD_KAFKA_RESP_ERR_NO_ERROR => [
//                [
//                    'topic' => $message->topic_name,
//                    'data' => json_decode($message->payload, true)
//                ]
//            ],
//            RD_KAFKA_RESP_ERR__PARTITION_EOF, RD_KAFKA_RESP_ERR__TIMED_OUT => [],
//            default => throw new Exception($message->errstr(), $message->err),
//        };
//    }
}
