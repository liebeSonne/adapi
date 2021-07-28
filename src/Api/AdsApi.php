<?php

namespace App\Api;

use App\Common\Request\RequestInterface;
use App\Common\Response\ApiResponseInterface;
use App\Common\Resources\ResourceInterface;
use App\Repository\AdsRepositoryInterface;
use App\Model\Ad;
use App\Model\Validator\ValidatorInterface;
use App\Model\Validator\AdValidator;
use App\Api\Exception\ApiException;

/**
 * API-ресурс рекламных объявлений.
 *
 */
class AdsApi extends ApiBase implements ResourceInterface
{
    /**
     * @var AdsRepositoryInterface
     */
    protected $repository;

    /**
     * @param RequestInterface $request
     * @param ApiResponseInterface $response
     * @param AdsRepositoryInterface $repository
     */
    public function __construct(
        RequestInterface $request,
        ApiResponseInterface $response,
        AdsRepositoryInterface $repository
    ) {
        parent::__construct($request, $response);
        $this->repository = $repository;
    }

    /**
     * Возвращает объект рекламного объявления из параметров запроса.
     *
     * @return \App\Model\Ad
     */
    protected function getAdFromRequest()
    {
        $params = $this->request->getRequestParams();
        $ad = new Ad();

        $ad->text = (string) $params['text'] ?? '';
        $ad->price = (int) $params['price'] ?? 0;
        $ad->limit = (int) $params['limit'] ?? 0;
        $ad->banner = (string) $params['banner'] ?? '';

        return $ad;
    }

    /**
     * Возвращает объект валидатора для объекта записи.
     *
     * @param mixed $item
     * @return ValidatorInterface|NULL
     */
    protected function getValidator($item): ?ValidatorInterface
    {
        if (!$item) {
            return null;
        }
        if (get_class($item) == Ad::class) {
            return new AdValidator($item);
        }
        return null;
    }

    /**
     * Метод обрабатывающий добавление записи объявления.
     * Считывает входные параметро POST-запроса:
     *    text, price, limit, banner
     *
     * @param array $args
     * @throws ApiException
     */
    public function addAd(array $args = []): void
    {
        $ad = $this->getAdFromRequest();

        $validator = $this->getValidator($ad);

        if (!$validator->isValid()) {
            $error = $validator->getFirstError();
            $this->response->set(400, $error, []);
            $this->response->display();
            return;
        }

        $ad->id = $this->repository->add($ad);

        if ($ad->id > 0) {
            $this->response->set(200, 'Ok', [
                'id' => $ad->id,
                'text' => $ad->text,
                'banner' => $ad->banner,
            ]);
            $this->response->display();
            return;
        }

        throw new ApiException('Can not add', 500);
    }

    /**
     * Метод обрабатывающий изменение записи объявления.
     * Считывает входные параметро POST-запроса:
     *    text, price, limit, banner
     *
     * @param array $args
     * @throws ApiException
     */
    public function editAd(array $args = []): void
    {
        $id = $args['id'] ?? $args[0] ?? null;

        if (!$id) {
            throw new ApiException('Invalid argument', 400);
        }

        $ad = $this->getAdFromRequest();

        $validator = $this->getValidator($ad);

        if (!$validator->isValid()) {
            $error = $validator->getFirstError();
            $this->response->set(400, $error, []);
            $this->response->display();
            return;
        }

        $result = $this->repository->edit($id, $ad);
        $ad->id = $id;

        if ($result) {
            $this->response->set(200, 'Ok', [
                'id' => $ad->id,
                'text' => $ad->text,
                'banner' => $ad->banner,
            ]);
            $this->response->display();
            return;
        }

        throw new ApiException('Can not edit', 500);
    }

    /**
     * Метод обрабатывающий запрос отображения запии объявления.
     *
     * @param array $args
     * @throws ApiException
     */
    public function relevantAd(array $args = []): void
    {
        $ad = $this->repository->getRelevant();

        if ($ad) {
            $this->repository->onShow($ad);

            $this->response->set(200, 'Ok', [
                'id' => $ad->id,
                'text' => $ad->text,
                'banner' => $ad->banner,
            ]);
            $this->response->display();
            return;
        }

        throw new ApiException('Can not show', 404);
    }
}
