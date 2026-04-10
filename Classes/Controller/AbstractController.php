<?php

declare(strict_types=1);

namespace Seven\TYPO3\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Seven\Api\Client;
use Seven\Api\Resource\Lookup\LookupResource;
use Seven\Api\Resource\Sms\SmsParams;
use Seven\Api\Resource\Sms\SmsResource;
use Seven\Api\Resource\Voice\VoiceParams;
use Seven\Api\Resource\Voice\VoiceResource;
use Seven\TYPO3\Domain\Model\Model;
use Seven\TYPO3\Util;
use TYPO3\CMS\Backend\Attribute\AsController;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Base controller for seven.io backend modules
 */
#[AsController]
abstract class AbstractController extends ActionController implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private Repository $repository;
    private string $resourceFQDN;

    public function __construct(
        private readonly ModuleTemplateFactory $moduleTemplateFactory,
        private readonly PersistenceManager $persistenceManager,
        private readonly FrontendUserRepository $feUserRepository,
        Repository $repo,
        string $fqdn,
    ) {
        $this->repository = $repo;
        $this->resourceFQDN = $fqdn;
    }

    public function indexAction(): ResponseInterface
    {
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $moduleTemplate->assignMultiple([
            'resources' => $this->repository->findAll(),
        ]);

        return $moduleTemplate->renderResponse();
    }

    public function deleteAction(int $uid): ResponseInterface
    {
        if ($this->repository->removeByUid($uid)) {
            $msg = 'delete_success';
            $title = 'success';
            $type = ContextualFeedbackSeverity::OK;
        } else {
            $msg = 'delete_error';
            $title = 'error';
            $type = ContextualFeedbackSeverity::ERROR;
        }

        $transRoot = 'LLL:EXT:seventypo3/Resources/Private/Language/locallang.xlf:';
        $this->addFlashMessage(
            LocalizationUtility::translate("$transRoot$msg", null, [$uid]),
            LocalizationUtility::translate("$transRoot$title"),
            $type,
            true,
        );

        return $this->redirect('index');
    }

    public function newAction(?array $configuration = null, ?string $type = null): ResponseInterface
    {
        if (!isset($configuration)) {
            $configuration = Util::getConfiguration();
        }

        if (!isset($configuration['apiKey'])) {
            $configuration['apiKey'] = Util::getConfiguration()['apiKey'];
        }

        $feUsers = [];
        $isLookup = false;

        if ('lookup' === $type) {
            $isLookup = true;
        } elseif (array_key_exists('type', $configuration)) {
            $isLookup = true;
            $type = $configuration['type'];
        } else {
            if (array_key_exists('_type', $configuration)) {
                $type = $configuration['_type'];
            } else {
                $type = $type ?? 'sms';
            }

            foreach ($this->feUserRepository->findAll() as $feUser) {
                if ('' !== $feUser->getTelephone()) {
                    $feUsers[] = $feUser;
                }
            }

            $configuration['_type'] = $type;
        }

        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $moduleTemplate->assignMultiple([
            'config' => (object)$configuration,
            'feUsers' => array_unique($feUsers),
        ]);

        return $moduleTemplate->renderResponse();
    }

    public function createAction(array $config): ResponseInterface
    {
        $this->log($config);

        /** @var Model $resource */
        $resource = new $this->resourceFQDN();
        $isLookup = isset($config['type']);
        $resource->setType($isLookup ? $config['type'] : $config['_type']);

        foreach ($config as $k => $v) {
            if ('' === $v) {
                unset($config[$k]);
            }
        }

        $type = $resource->getType();
        unset($config['_type'], $config['feUsers']);
        $resource->setConfig($config);

        $this->repository->add($resource);
        $this->persistenceManager->persistAll();

        $apiKey = Util::getConfiguration()['apiKey'];
        $client = new Client($apiKey, 'typo3');
        $res = $this->callApi($client, $type, $isLookup, $config);
        $this->log($res);

        $resource->setResponse(is_string($res) ? $res : json_encode($res));
        $this->repository->update($resource);
        $this->persistenceManager->persistAll();

        return $this->redirect('show', null, null, ['resource' => $resource]);
    }

    protected function renderShowAction(Model $resource): ResponseInterface
    {
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $moduleTemplate->assign('resource', $resource);

        return $moduleTemplate->renderResponse();
    }

    private function callApi(Client $client, string $type, bool $isLookup, array $config): mixed
    {
        if ($isLookup) {
            $lookupResource = new LookupResource($client);
            $number = $config['number'];

            return match ($type) {
                'hlr' => $lookupResource->hlr($number),
                'cnam' => $lookupResource->cnam($number),
                'mnp' => $lookupResource->mnp($number),
                'format' => $lookupResource->format($number),
            };
        }

        if ('voice' === $type) {
            $voiceResource = new VoiceResource($client);
            $params = new VoiceParams($config['text'], $config['to']);

            return $voiceResource->call($params);
        }

        // SMS
        $smsResource = new SmsResource($client);
        $params = new SmsParams($config['text'], $config['to']);

        if (!empty($config['from'])) {
            $params->setFrom($config['from']);
        }
        if (!empty($config['flash'])) {
            $params->setFlash(true);
        }
        if (!empty($config['foreign_id'])) {
            $params->setForeignId($config['foreign_id']);
        }
        if (!empty($config['label'])) {
            $params->setLabel($config['label']);
        }
        if (!empty($config['udh'])) {
            $params->setUdh($config['udh']);
        }
        if (!empty($config['ttl'])) {
            $params->setTtl((int)$config['ttl']);
        }
        if (!empty($config['delay'])) {
            $params->setDelay(new \DateTime($config['delay']));
        }
        if (!empty($config['performance_tracking'])) {
            $params->setPerformanceTracking(true);
        }

        return $smsResource->dispatch($params);
    }

    private function log(mixed $data): void
    {
        if (!Environment::getContext()->isProduction()) {
            $this->logger->debug(json_encode($data));
        }
    }
}
