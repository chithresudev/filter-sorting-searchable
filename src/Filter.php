<?php
namespace Devchithu\FilterSortingSearchable;

Class Filter 
{

    /**
     * Static public function fieldBinding for scripts
     * 
     * @return
     */

     public static function createPopOver(...$customPopOver) {

        $collection = json_encode($customPopOver[0], true);
        $collection = json_decode($collection, true);

        $sorting = $collection['sorting'] ?? false;
        $filter = $collection['filter'] ?? false;
        $field_name = $collection['field_name'] ?? false;
        $type = $collection['type'] ?? false;
        $label_name = $collection['label_name'] ?? false;
        $sorting_custom_label = $collection['sorting_custom_label'] ?? [];
        $filter_multiple_options = $collection['multiple_option'] ?? [];
        $date_range = $collection['date_range'] ?? [];
        $custom_design = $collection['custom_design'] ?? [];

        $createPopOver = ucwords($label_name ? $label_name : str_replace('_', ' ', $field_name)) . 
         '<span data-pop-over="' . ($field_name) . 'PopOver">' . self::icon($field_name) . 
        '</span>&nbsp;<img src=' . asset('filter-icon.png') .' data-pop-over="' . ($field_name) . 'PopOver"
        alt="Sq1cloud" type="button" class="me-4" style="max-height:12px;">

        <div hidden>
        <div id="' . ($field_name) . 'PopOverContentShow" style="min-width:227px;">
         <ul class=" p-0 m-0">';

         if($sorting)
         {
            $createPopOver .= self::sorting($field_name, $sorting_custom_label);
         }

         if($filter)
         {
            $createPopOver .= self::filter($field_name, $type, $filter_multiple_options, $date_range, $custom_design);
         }

            $createPopOver .= '</div></ul></div>';

         echo $createPopOver;

    }

    /**
     * Static public function filterButton template
     * 
     * @return
     */

     private static function filter($field_name, $type, $filter_multiple_options, $date_range, $custom_design) {

        $filterOptions = '';
        $append_option = '';

        if($type == 'select' || $type == 'checkbox' || $type == 'radio')
        {

            $param = app('request')->$field_name;
            $checkParams = explode(',', $param);

            foreach ($filter_multiple_options as $key => $multiple_option) {

                if($type == 'select') {
                    $append_option .= '

                    <option ' . (($param == $multiple_option) ? 'selected' : '') . ' value="' . $multiple_option. '">'. ucfirst($multiple_option) . '</option>'
                    ;
                }

                else if($type == 'checkbox') {
                    
                    $filterOptions .= '
                    <div class="form-check">
                        <input class="form-check-input" name="' . $field_name . '"  type="checkbox"
                        ' . ((in_array($multiple_option, $checkParams)) ? 'checked' : '') . '
                        value="' . $multiple_option . '" id="dynamicCheckBox' . $key .'">
                        <label class="form-check-label" for="dynamicCheckBox' . $key .'">
                        '. ucfirst($multiple_option) . '
                        </label>
                        </div>';
                }

                else {
                    $filterOptions .= '
                    <div class="form-check border-hover">
                    <input class="form-check-input" type="radio" value="' . $multiple_option . '"
                        name="' . $field_name . '" id="dynamicRadio' . $key .'"
                        ' . ((in_array($multiple_option, $checkParams)) ? 'checked' : '') . '
                        >
                    
                        <label class="form-check-label" for="dynamicRadio' . $key .'">
                        '. ucfirst($multiple_option) . '
                    </label>
                </div>';
                }

            }

            if($type == 'select') {
                $filterOptions .= '<select class="form-select" name="' . $field_name . '"  aria-label="Default select example"><option selected>select Option</option>
                ' . $append_option .
                '</select>';
            }

        }
        

        else {
            
            if($type != 'no') {
            $filterOptions .=  '
            <div class="rounded">
            <div class="custom-search-button d-flex ">
                <input type="' . ($type ? $type : 'text') . '" placeholder="Search by ' . ucfirst(str_replace('_', ' ', $field_name)) . '"
                    name="' . $field_name .'"
                    value="' . app('request')->$field_name . '"
                    class="form-control rounded-0  custom-search-button-2  "
                    >
                 
            </div>
        </div>
        ';
        }
    }

        if(count($date_range)) {
        
            $start = $date_range[0];
            $end = $date_range[1];
    
            $filterOptions .= '
    
            <div>
                <div class="row mt-3">
                <div class="col-md-6">
                <label class="small">Start Date</label>
                    <input type="date" name="start_date"
                        value="' . $start . '" class=" date-from-input border-hover-input form-control p-1"
                        />
            </div>
    
            <div class="col-md-6">
            <label class="small">End Date</label>
                    <input type="date" name="end_date" value=" ' . $end . '"
                        class="date-from-input border-hover-input form-control p-1"
                        />
            </div>
            </div>
            </div>';
        
        }


        if($custom_design) {
            $filterOptions .= self::customDesign($custom_design);
        }


        $filterOptions .= '
        <div class="row pt-3">
            <div class="col-md-6">
                <button
                    class="btn btn-sm btn-block col-12 text-white" style="background-color: #747474">Cancel</button>
            </div>
            <div class="col-md-6">
                <button
                    class="btn btn-sm btn-block col-12 text-white" style="background-color: #687ff5">Apply
                    Filter</button>
            </div>
            </div>
           ';

        return "<form onsubmit='event.preventDefault();submitFilterForm(this);'>$filterOptions</form> ";

    }

    private static function customDesign($custom_design) {
        return $custom_design;
    }

    private static function sorting($field_name, $sorting_custom_label) {
        $sort_direction = app('request')->sort_direction;
        $sort_field = app('request')->sort_field;
        return '
        <div class="form-check p-2 border-hover ' . (($sort_field == $field_name) && ($sort_direction == "asc") ? 'bg-info' : '') . '">
        <label class="form-check-label " for="Asending" role="button">
            <i class="fa-solid fa-arrow-up me-1"></i>
            <a href="' . app('request')->fullUrlWithQuery(['sort_field' => $field_name, 'sort_direction' => 'asc']) . '">
            ' . ($sorting_custom_label[0] ?? false ? $sorting_custom_label[0] : 'Sort Ascending') . '
            </a>
        </label>
        </div>
        <div class="form-check border-hover p-2 ' . (($sort_field == $field_name) && ($sort_direction == "desc") ? 'bg-info' : '') . '">
            <label class="form-check-label" for="Desending" role="button">
                <i class="fa-solid fa-arrow-down me-1"></i>
                <a href="' . app('request')->fullUrlWithQuery(['sort_field' => $field_name, 'sort_direction' => 'desc']) . '">
                ' . (($sorting_custom_label[1] ?? false ? $sorting_custom_label[1] : ' Sort Desending')) . '

                </a>
            </label>
        </div><hr class="border-secondary mt-2"/>
    ';

    }

    private static function icon($field_name) {
        $icon = '';
        if(app('request')->sort_field == $field_name) {
            $icon_dir = app('request')->sort_direction == 'desc' ? 'down' : 'up';
            $icon .= ' <i class="fa-solid fa-arrow-' . $icon_dir .'"></i>';
        }

        return $icon;
        
    }

        /**
     * Static public function searchable template
     * 
     * @return
     */

     public static function search() 
     {
                 echo '
                 <span>
                 <form class="row g-3">
                 <div class="col-auto">
                 <input type="search" name="search" '. (app('request')->search ? 'value=' . app('request')->search : '') . ' class="form-control" id="searchable" placeholder="Search here...">
                 </div>
                 <div class="col-auto">
                 <button type="submit" class="btn btn-primary mb-3">Search</button>
                 </div>
             </form>
             </span>
                 ';
 
     }
     
     /**
      * Static public function bingdingparams template
      * 
      * @return
      */
      public static function bindingParams()
      {
 
         $appRequests = app('request')->except(['page', 'sort_direction']);
         
         if($appRequests) {
         $bingParams = '<div>';
 
         foreach ($appRequests as $field_name => $field_value) {
             $bingParams .= '<div type="button" class="fw-normal d-inline-block filtered-container badge text-bg-light border shadow-sm py-2 me-2 mb-2 ls-1 filtered-tags" data-param-field="' . $field_name . '">
             <span data-pop-over="' . $field_name . 'PopOver"> ';
 
                    if($field_name == 'sort_field') {
                     $bingParams .= 'Sort by '  . strtoupper(app('request')->sort_direction) . ' in ' . ucwords(str_replace('_', ' ' ,$field_value));
                     
                    }    else  {
                     $bingParams .= ucwords(str_replace('_', ' ' ,$field_name)) . ' : ' . $field_value;
                 
                    }
                  
             $bingParams .= '</span>
             <button type="button"
             class="btn-close ms-5" onclick="filterRemove(this)"></button></div>';
         }
 
         $bingParams .= '<button class="btn btn-secondary rounded-1" onclick="clearALLParams()">Clear All</button>';
         $bingParams .= '</div>';
 
         echo  $bingParams;
     }            
    
    }
}
