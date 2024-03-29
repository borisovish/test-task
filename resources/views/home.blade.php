@extends('layouts.lte')

@section('content')

   <div class="box ">
   <div class="box-header with-border">
        <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
        </div>
<h5>  {{$title}} </h5>
   </div>
   <div class='box-body '>

       <div id="jqgrid-wrapper" class='col-sm-12 page-content'>
            <table id="jqgrid" > </table>
            <div id="jqgrid-pager"></div>
       </div>
   </div>
</div>


@endsection

@section('js')


    <script src="{{asset('/js/jqgrid4.13.1-scripts.js')}}"></script>

    <script>
        $.jgrid = $.jgrid || {};
        $.jgrid.no_legacy_api = true;

    $(function () {
            var grid_selector = "#jqgrid";
            var pager_selector = "#jqgrid-pager";
            $(window).on('resize.jqGrid', function () {
                $(grid_selector).jqGrid( 'setGridWidth', $(".page-content").width() );
            })
          jQuery(grid_selector).jqGrid({
                    url: '{{URL::current()}}/loaddata',
                    mtype: "post",
                    datatype: "json",
                    height: 500,

                    colModel:[
                            {name: 'id', index: 'id', width: 40,editable: false,
                               searchoptions: {sopt: ['eq', 'ne', 'cn', 'nc']},
                            },
                            {name: 'id магазина', index: 'advcampaign_id', width: 40,editable: false,
                               searchoptions: {sopt: ['eq', 'ne', 'cn', 'nc']},
                            },
                            {name: 'id заказа', index: 'order_id', width: 80,editable: false,
                               searchoptions: {sopt: ['eq', 'ne', 'cn', 'nc']},
                            },
                            {name: 'сумма заказа', index: 'cart', width: 40,editable: false,
                               searchoptions: {sopt: ['eq', 'ne', 'le', 'ge']},
                            },
                            {name: 'Валюта', index: 'currency', width: 30,editable: false,
                               searchoptions: {sopt: ['cn', 'eq', 'ne',  'nc']},
                            },
                            {name: 'статус заказа', index: 'status', width: 50,editable: false,
                               searchoptions: {sopt: ['cn', 'eq', 'ne',  'nc']},
                            },
                            {
                              label: "время создания заказа",
                              name: 'action_date',
                              width: 50,
                              sorttype: 'date',
                              formatter: 'date',
                              srcformat: 'Y-m-d ',
                              stype: 'text',
                              newformat: 'd.m.yy',
                              editable: false,
                              searchoptions: {
                                  dataInit: function(element) {
                                      $(element).datepicker({
                                          id: 'orderDate_datePicker',
                                          dateFormat: 'dd.mm.yy',
                                          language: "ru",
                                          maxDate: new Date(2020, 0, 1),
                                          showOn: 'focus'
                                      });
                                  },
                                  sopt: ['gt', 'lt']
                              }
                            },
                            {name: 'Примечание', index: 'description', editable: true, edittype: "textarea",
                                 editoptions: {rows: "2", cols: "20"},
                                 sortable: true, searchoptions:
                                 {sopt: ['cn', 'nc']}
                            },
                    ],

                    cmTemplate: { autoResizable: true, editable: false },
                    iconSet: "fontAwesome",
                    guiStyle: "bootstrap",
                    rowNum: '{{env("rowNum",20)}}',
                    viewrecords: false,
                    autoencode: true,
                    sortable: true,
                    toppager: true,
                    pager: true,
                    rownumbers: false,
                    pagerRightWidth: 150,
                    search: true,
                    height: "100%",
                    // shrinkToFit: true,
                    autowidth: true,
                    rowList:{!!env("rowList","[20,50,80,120,'10000:Все']")!!},
                    // pager: '#jqgrid-pager',
                    sortname: 'id',
                    sortorder:'{{env("sortorder","ASC")}}',
                    multiselect: false,
                    gridview: false,
                    "postData":{
                       "_token":"{{csrf_token()}}"
                     },
                    inlineEditing: {
                        keys: true
                    },
                    formEditing: {
                        width: 400,
                        closeOnEscape: true,
                        closeAfterEdit: true,
                        savekey: [true, 13]
                    },
                    formViewing: {
                        labelswidth: ""
                    },
                    searching: {
                        multipleSearch: true,
                        closeOnEscape: true,
                        searchOnEnter: true,
                        searchOperators: true,
                        width: 550
                    },
                    editing: {
                        width: 550
                    },
                    singleSelectClickMode: "selectonly", // optional setting
        });
        jQuery(grid_selector).jqGrid('gridResize');


        jQuery(grid_selector).jqGrid('navGrid', {edit:false,add:false,del:false, search:true},
          // Отключаем от тулбара редактирование, добавление и удаление записей. На тулбаре останутся только две кнопки: "Поиск" и "Обновить"
            {width:700}, // Опции окон редактирования
            {}, // Опции окон добавления
            {}, // Опции окон удаления
            {
                multipleSearch:true, // Поиск по нескольким полям
                multipleGroup:true, // Сложный поиск с подгруппами условий
                showQuery: true // Показывать превью условия
            }
        );
        jQuery(grid_selector).jqGrid('navSeparatorAdd', {sepclass : "ui-separator",sepcontent: ''});

        // jQuery(grid_selector).jqGrid('filterToolbar');

        $(window).triggerHandler('resize.jqGrid');
      jQuery(".ui-pg-div").removeClass().addClass("btn btn-xs btn-default");


    });
</script>
@stop
@section('header')
 <h1 class="title"><i class="fa fa-dashboard"></i>Orders</h1>

@stop
