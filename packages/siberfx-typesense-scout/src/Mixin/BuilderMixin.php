<?php

namespace Siberfx\Typesense\Mixin;

use Closure;
use Laravel\Scout\Builder;

/**
 * Class BuilderMixin.
 *
 * @mixin Builder
 *
 * @date 09/10/2021
 *
 * @author Selim Görmüş <info@siberfx.com>
 */
class BuilderMixin
{
    public function count(): Closure
    {
        return function () {
            return $this->engine()
                ->getTotalCount($this->engine()
                    ->search($this));
        };
    }

    /**
     * @param  string  $column
     * @param  float  $lat
     * @param  float  $lng
     * @param  string  $direction
     */
    public function orderByLocation(): Closure
    {
        return function (string $column, float $lat, float $lng, string $direction = 'asc') {
            $this->engine()
                ->orderByLocation($column, $lat, $lng, $direction);

            return $this;
        };
    }

    /**
     * @param  array|string  $groupBy
     */
    public function groupBy(): Closure
    {
        return function (array|string $groupBy) {
            $groupBy = is_array($groupBy) ? $groupBy : func_get_args();
            $this->engine()
                ->groupBy($groupBy);

            return $this;
        };
    }

    /**
     * @param  int  $groupByLimit
     */
    public function groupByLimit(): Closure
    {
        return function (int $groupByLimit) {
            $this->engine()
                ->groupByLimit($groupByLimit);

            return $this;
        };
    }

    /**
     * @param  string  $startTag
     */
    public function setHighlightStartTag(): Closure
    {
        return function (string $startTag) {
            $this->engine()
                ->setHighlightStartTag($startTag);

            return $this;
        };
    }

    /**
     * @param  string  $endTag
     */
    public function setHighlightEndTag(): Closure
    {
        return function (string $endTag) {
            $this->engine()
                ->setHighlightEndTag($endTag);

            return $this;
        };
    }

    /**
     * @param  int  $limitHits
     */
    public function limitHits(): Closure
    {
        return function (int $limitHits) {
            $this->engine()
                ->limitHits($limitHits);

            return $this;
        };
    }

    /**
     * @param  array  $facetBy
     */
    public function facetBy(): Closure
    {
        return function (array $facetBy) {
            $this->engine()
                ->facetBy($facetBy);

            return $this;
        };
    }

    /**
     * @param  int  $maxFacetValues
     */
    public function setMaxFacetValues(): Closure
    {
        return function (int $maxFacetValues) {
            $this->engine()
                ->setMaxFacetValues($maxFacetValues);

            return $this;
        };
    }

    /**
     * @param  string  $facetQuery
     */
    public function facetQuery(): Closure
    {
        return function (string $facetQuery) {
            $this->engine()
                ->facetQuery($facetQuery);

            return $this;
        };
    }

    /**
     * @param  array  $includeFields
     */
    public function setIncludeFields(): Closure
    {
        return function (array $includeFields) {
            $this->engine()
                ->setIncludeFields($includeFields);

            return $this;
        };
    }

    /**
     * @param  array  $excludeFields
     */
    public function setExcludeFields(): Closure
    {
        return function (array $excludeFields) {
            $this->engine()
                ->setExcludeFields($excludeFields);

            return $this;
        };
    }

    /**
     * @param  array  $highlightFields
     */
    public function setHighlightFields(): Closure
    {
        return function (array $highlightFields) {
            $this->engine()
                ->setHighlightFields($highlightFields);

            return $this;
        };
    }

    /**
     * @param  array  $pinnedHits
     */
    public function setPinnedHits(): Closure
    {
        return function (array $pinnedHits) {
            $this->engine()
                ->setPinnedHits($pinnedHits);

            return $this;
        };
    }

    /**
     * @param  array  $hiddenHits
     */
    public function setHiddenHits(): Closure
    {
        return function (array $hiddenHits) {
            $this->engine()
                ->setHiddenHits($hiddenHits);

            return $this;
        };
    }

    /**
     * @param  array  $highlightFullFields
     */
    public function setHighlightFullFields(): Closure
    {
        return function (array $highlightFullFields) {
            $this->engine()
                ->setHighlightFullFields($highlightFullFields);

            return $this;
        };
    }

    /**
     * @param  int  $highlightAffixNumTokens
     */
    public function setHighlightAffixNumTokens(): Closure
    {
        return function (int $highlightAffixNumTokens) {
            $this->engine()
                ->setHighlightAffixNumTokens($highlightAffixNumTokens);

            return $this;
        };
    }

    /**
     * @param  string  $infix
     */
    public function setInfix(): Closure
    {
        return function (string $infix) {
            $this->engine()
                ->setInfix($infix);

            return $this;
        };
    }

    /**
     * @param  int  $snippetThreshold
     */
    public function setSnippetThreshold(): Closure
    {
        return function (int $snippetThreshold) {
            $this->engine()
                ->setSnippetThreshold($snippetThreshold);

            return $this;
        };
    }

    /**
     * @param  bool  $exhaustiveSearch
     */
    public function exhaustiveSearch(): Closure
    {
        return function (bool $exhaustiveSearch) {
            $this->engine()
                ->exhaustiveSearch($exhaustiveSearch);

            return $this;
        };
    }

    /**
     * @param  bool  $useCache
     */
    public function setUseCache(): Closure
    {
        return function (bool $useCache) {
            $this->engine()
                ->setUseCache($useCache);

            return $this;
        };
    }

    /**
     * @param  int  $cacheTtl
     */
    public function setCacheTtl(): Closure
    {
        return function (int $cacheTtl) {
            $this->engine()
                ->setCacheTtl($cacheTtl);

            return $this;
        };
    }

    /**
     * @param  bool  $prioritizeExactMatch
     */
    public function setPrioritizeExactMatch(): Closure
    {
        return function (bool $prioritizeExactMatch) {
            $this->engine()
                ->setPrioritizeExactMatch($prioritizeExactMatch);

            return $this;
        };
    }

    /**
     * @param  string  $prefix
     */
    public function setPrefix(): Closure
    {
        return function (string $prefix) {
            $this->engine()
                ->setPrefix($prefix);

            return $this;
        };
    }

    /**
     * @param  bool  $enableOverrides
     */
    public function enableOverrides(): Closure
    {
        return function (bool $enableOverrides) {
            $this->engine()
                ->enableOverrides($enableOverrides);

            return $this;
        };
    }

    /**
     * @param  array  $searchRequests
     */
    public function searchMulti(): Closure
    {
        return function (array $searchRequests) {
            $this->engine()->searchMulti($searchRequests);

            return $this;
        };
    }
}
