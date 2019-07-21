<?php

namespace Arc\Support;

use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Collection;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection as ResourceCollection;
use League\Fractal\Resource\Item as ResourceItem;

abstract class Action
{
    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @var Manager
     */
    private $fractal;

    protected function fractal()
    {
        if (! ($this->fractal instanceof ResponseFactory)) {
            try {
                $this->fractal = Container::getInstance()->make(Manager::class);
                $this->fractal->parseIncludes(request()->query('include', ''));
                $this->fractal->parseExcludes(request()->query('exclude', ''));
            } catch (BindingResolutionException $e) {
                report($e);
                abort(500);
            }
        }

        return $this->fractal;
    }

    /**
     * @return \Illuminate\Routing\ResponseFactory|mixed
     */
    protected function response(): \Illuminate\Contracts\Routing\ResponseFactory
    {
        if (! ($this->responseFactory instanceof ResponseFactory)) {
            try {
                $this->responseFactory = Container::getInstance()->make(ResponseFactory::class);
            } catch (BindingResolutionException $e) {
                report($e);
                abort(500);
            }
        }

        return $this->responseFactory;
    }

    /**
     * @param        $data
     * @param string $transformer
     * @param array  $meta
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function transform($data, string $transformer, array $meta = []): JsonResponse
    {
        $transformer = new $transformer;
        $this->fractal()->getSerializer()->meta($meta);

        if ($data instanceof Collection) {
            $resource = new ResourceCollection($data, $transformer);
        } else {
            $resource = new ResourceItem($data, $transformer);
        }

        return $this->response()->json($this->fractal()->createData($resource)->toArray());
    }

    /**
     * @return \Illuminate\Routing\UrlGenerator|mixed
     */
    protected function url(): UrlGenerator
    {
        try {
            return Container::getInstance()->make(UrlGenerator::class);
        } catch (BindingResolutionException $e) {
            report($e);
            abort(500);
        }
    }
}