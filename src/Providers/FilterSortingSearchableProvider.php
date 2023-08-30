<?php

namespace Devchithu\FilterSortingSearchable\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class FilterSortingSearchableProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../public/' => public_path('/'),
        ], 'filter-sorting-searchable');

        
        $this->publishes([
            __DIR__.'/../../src/CustomFilter/CustomFilterTrait.php' => app_path('CustomFilter/CustomFilterTrait.php'),
        ], 'customFilterTrait');

        
        $this->loadViewsFrom(__DIR__.'/../../public/filter-sorting-searchable.js', 'filter-sorting-searchable.js');

        

        Blade::directive('filterSort', function ($input_array) {

            return "<?php Devchithu\FilterSortingSearchable\Filter::createPopOver({$input_array}) ?>";

        });

        Blade::directive('searchable', function ($input_search_array) {

            return "<?php Devchithu\FilterSortingSearchable\Filter::search({$input_search_array}) ?>";
    
            });

        Blade::directive('bindingParams', function ($input_params) {

            return "<?php Devchithu\FilterSortingSearchable\Filter::bindingParams({$input_params}) ?>";
    
            });
            
        }
}
