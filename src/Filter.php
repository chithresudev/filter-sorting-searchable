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
        $custom_design = $collection['custom_design'] ?? [];
        $table_column_switcher = $collection['table_column_switcher'] ?? '';

        $field_label_generate = ucwords($label_name ? $label_name : str_replace('_', ' ', $field_name));
        
        $createPopOver = $field_label_generate;

        if($sorting || $filter) {
        $createPopOver .= 
         '<span data-pop-over="' . ($field_name) . 'PopOver">' . self::icon($field_name) . 
        '</span>';
        
        $createPopOver .= '<span class="position-relative">
        <svg width="20" role="button" height="20" data-pop-over="' . ($field_name) . 'PopOver" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M4.16669 3.33331H15.8334C16.0635 3.33331 16.25 3.51986 16.25 3.74998C16.25 3.84013 16.2208 3.92786 16.1667 3.99998L11.6667 9.99998V16.8258C11.6667 17.0559 11.4801 17.2425 11.25 17.2425C11.1853 17.2425 11.1215 17.2274 11.0637 17.1985L8.33336 15.8333V9.99998L3.83336 3.99998C3.69529 3.81588 3.7326 3.55472 3.91669 3.41665C3.98881 3.36255 4.07654 3.33331 4.16669 3.33331Z" fill="black"></path>
        </svg>
        </span>';

        if(app('request')->has($field_name)) {
            $createPopOver .= '<span
            class="position-absolute top-0 start-100 translate-middle p-1 border border-light rounded-circle" style="background:#687ff5">
            <span class="visually-hidden">filters enabled</span>
        </span>';
        }
    
        $createPopOver .= '
        <div hidden>
        <div id="' . ($field_name) . 'PopOverContentShow" style="min-width:227px;">
         <ul class=" p-0 m-0">';

         if($sorting)
         {
            $createPopOver .= self::sorting($field_name, $sorting_custom_label);
         }

         if($filter)
         {
            $createPopOver .= self::filter($field_name, $type, $filter_multiple_options, $custom_design);
         }

            $createPopOver .= '</div></ul></div>';
        }

        if($table_column_switcher) {
            $createPopOver .= '<span data-table-column-switcher="' .$table_column_switcher. '"></span>';
        }

         echo $createPopOver;

    }

    /**
     * Static public function filterButton template
     * 
     * @return
     */

     private static function filter($field_name, $type, $filter_multiple_options, $custom_design) {

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

        if($custom_design) {
            $filterOptions .= self::customDesign($custom_design);
        }

        $filterOptions .= '
        <div class="row pt-3">
            <div class="col-md-6 mb-2">
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
        <div class="form-check p-2 border-hover"' . (($sort_field == $field_name) && ($sort_direction == "asc") ? 'style="background:#d9daf8;color:#687ff5' : '') . '">
        <label class="form-check-label font-14 " for="Asending" role="button">
            <i class="fa-solid fa-arrow-up me-1"></i>
            <a href="' . app('request')->fullUrlWithQuery(['sort_field' => $field_name, 'sort_direction' => 'asc']) . '">
            ' . ($sorting_custom_label[0] ?? false ? $sorting_custom_label[0] : 'Sort Ascending') . '
            </a>
        </label>
        </div>
        <div class="form-check border-hover p-2"' . (($sort_field == $field_name) && ($sort_direction == "desc") ? 'style="background:#d9daf8;color:#687ff5' : '') . '">
            <label class="form-check-label font-14"  for="Desending" role="button">
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
            $icon .= ' <i class="fa-solid fa-arrow-' . $icon_dir .'" style="color:#687ff5; font-size:14px"></i>';
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
      public static function bindingParams(...$custom_styles)
      {
 
        $bindingparams = json_encode($custom_styles[0], true);
        $bindingparams = json_decode($bindingparams, true);
 
         $appRequests = app('request')->except(['page', 'sort_direction']);
         
         if($appRequests) {
         $bingParams = '';
 
         foreach ($appRequests as $field_name => $field_value) {
            $sortNameConvert = ($field_name == 'sort_field') ? $field_value : $field_name;

            $bingParams .= '<div type="button" class="fw-normal d-inline-block filtered-container badge text-bg-light border shadow-sm py-2 px-3 me-2 mb-2 ls-1 filtered-tags rounded-5 ' . 
             ($field_name == 'sort_field' ?
             ($bindingparams['sorting_style_class'] ?? null) : ($bindingparams['filter_style_class'] ?? null)) .
             
             '"data-param-field="' . $sortNameConvert . '">
             <span data-pop-over="' . $sortNameConvert . 'PopOver"> ';
 
                    if($field_name == 'sort_field') {
                     $bingParams .= 'Sort by '  . strtoupper(app('request')->sort_direction) . ' in ' . ucwords(str_replace('_', ' ' ,$field_value));
                     
                    }    else  {
                     $bingParams .= ucwords(str_replace('_', ' ' ,$field_name)) . ' : ' . $field_value;
                 
                    }
                  
             $bingParams .= '</span>
             <button type="button"
             class="btn-close ms-5" onclick="filterRemove(this)"></button></div>';
         }
 
         $bingParams .= '<button class="btn btn-secondary rounded-4" onclick="clearALLParams()">Clear All</button>';
 
         echo  $bingParams;
     }            
    }

         
     /**
      * Static public function dynamic table column hidden template
      * 
      * @return
      */
      public static function tableColumnSwitcher($dynamic_id = null)
      {

            $design = '
            <div class="dropdown ms-3">
                <button type="button" class="btn btn-info light sharp" data-bs-toggle="dropdown" aria-expanded="false">
                    <svg width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24"></rect>
                            <circle fill="#000000" cx="5" cy="12" r="2"></circle>
                            <circle fill="#000000" cx="12" cy="12" r="2"></circle>
                            <circle fill="#000000" cx="19" cy="12" r="2"></circle>
                        </g>
                    </svg>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <div id="listColumnSwitcher_' . ($dynamic_id ?? NULL) . '" class="switches p-3 text-nowrap">
                   
                    </div>
                    <hr>
                    <p role="button" style="text-align:center" onClick="myClearAll()"> Get Default
                    </p>
              
                </div>
            </div>';
          echo $design;
      }
}
