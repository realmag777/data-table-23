<!DOCTYPE html>
<html>
    <head>
        <title>Tableon Pass Manager</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="assets/js/data-table-23/data-table-23.css" />
        <link rel="stylesheet" href="assets/css/growls.css" />
        <link rel="stylesheet" href="assets/css/switcher-23.css" />
        <link rel="stylesheet" href="assets/css/gallery.css" />
        <link rel="stylesheet" href="assets/css/general.css" />
    </head>
    <body>

        <div class='data-table-23 tableon-data-table tableon_default_tables ' data-skin="" data-post-type="post" id='passmanager'>
            <input type="search" data-key="name" value="" minlength="0" class="tableon-text-search" placeholder="" />
            <div class="tableon-order-select-zone"></div>
            <div class="tableon-clearfix"></div>
            <div class="table23-place-loader">Loading ...</div>
            <table class="tableon-table"></table>
        </div>

        <script>
            var table_options = {
                mode: 'ajax',
                ajax_url: 'http://labs.pluginus.net/passmanager/processor.php',
                heads: {id: 'ID', name: 'Name', gallery: 'Photos', match: 'Match %', action: 'Action', action2: 'Action 2'},
                orders: {name: 'asc', match: 'desc'},
                editable: [],
                show_print_btn: 0,
                compact_view_width: 600,
                use_flow_header: 1,
                custom_field_keys: [],
                pagination: {
                    position: 'tb',
                    next: {
                        class: 'tableon-btn',
                        content: '&gt;'
                    },
                    prev: {
                        class: 'tableon-btn',
                        content: '&lt;'
                    },
                    input: {
                        class: 'tableon-form-control'
                    }
                },
                per_page_sel_position: 'tb',
                per_page_sel_pp: [10, 20, 30, 40, 50, 60, 70, 80, 90, 100],
                orderby: 'match',
                order: 'desc',
                per_page: 10,
                current_page: 0,
                draw_cells: {
                    action: function (value, td, o_table) {
                        let id = 'sw_' + Math.random().toString(36).substring(7);
                        let pid = td.getAttribute('data-pid');

                        let switcher = document.createElement('div');

                        switcher.innerHTML = `
                        <input type="hidden" name="is_checked" value="0">
                        <input type="checkbox" id="${id}" class="switcher23" value="0" data-n="data-n" data-post-id="${pid}" data-event="check-customer" data-custom-data="" data-name="is_checked">
                        <label for="${id}" class="switcher23-toggle"><span></span></label>
                        `;

                        o_table.init_switcher(switcher.querySelector('.switcher23'));

                        return switcher;
                    },
                    gallery: function (imgs, td, o_table) {
                        let gallery = document.createElement('div');
                        let res = '';

                        if (imgs.length > 0) {
                            let id = 'gall_' + Math.random().toString(36).substring(7);
                            let tpl1 = '';
                            let tpl2 = '';


                            for (let i = 0; i < imgs.length; i++) {
                                tpl1 += `<div class="tableon-gallery-nav"><img src="${imgs[i]}" loading="lazy" alt="" /><a href="#tableon-gallery-lightbox-${id}-${i}">&nbsp;</a></div>`;
                            }

                            for (let i = 0; i < imgs.length; i++) {

                                let prev = '';
                                let next = '';

                                if (typeof imgs[i - 1] !== 'undefined') {
                                    prev = `<div class="tableon-gallery-nav tableon-gallery-nav-left"><a href="#tableon-gallery-lightbox-${id}-${i - 1}"><img src="${imgs[i - 1]}" loading="lazy" width="50" alt="" /></a></div>`;
                                }

                                if (typeof imgs[i + 1] !== 'undefined') {
                                    next = `<div class="tableon-gallery-nav tableon-gallery-nav-right"><a href="#tableon-gallery-lightbox-${id}-${i + 1}"><img src="${imgs[i + 1]}" loading="lazy" width="50" alt="" /></a></div>`;
                                }

                                tpl2 += `<div class="tableon-gallery-lightbox" id="tableon-gallery-lightbox-${id}-${i}">
                                        ${prev}&nbsp;<div class="tableon-gallery-content"><img src="${imgs[i]}" loading="auto" alt="" />           
                                            <div class="tableon-gallery-title">&nbsp;</div>
                                            <a class="tableon-gallery-close" href="#/tableon-gallery-${id}"></a>
                                        </div>${next}</div>`;
                            }

                            res = `<div id="tableon-gallery-container-${id}">
                            <div class="tableon-gallery tableon-gallery-cell" id="tableon-gallery-${id}">${tpl1}</div>
                                ${tpl2}</div>`;

                        }

                        gallery.innerHTML = res;
                        return gallery;
                    },
                    action2: function (value, td, o_table) {
                        
                        let a2 = document.createElement('div');
                        a2.innerHTML='Hello World';
                        
                        a2.addEventListener('click', function(e){
                            console.log(e);
                        });

                        return a2;

                    }
                }
            };

            var table_id = 'passmanager';
            var table = null;

            //+++

            window.addEventListener('load', function () {

                if (typeof DataTable23 === 'undefined') {
                    return;
                }


                //init data tables
                table = new TABLEON_GeneratedTables(table_options, table_id);

                //functions for switcher events
                table.init_switcher = function (button) {
                    let _this = this;
                    button.addEventListener('click', function (e) {

                        e.stopPropagation();
                        if (this.value > 0) {
                            this.value = 0;
                            this.previousSibling.value = 0;
                            this.removeAttribute('checked');
                        } else {
                            this.value = 1;
                            this.previousSibling.value = 1;
                            this.setAttribute('checked', 'checked');
                        }

                        //Trigger the event
                        if (this.getAttribute('data-event').length > 0) {

                            fetch(_this.settings.ajax_url, {
                                method: 'POST',
                                credentials: 'same-origin',
                                body: _this.prepare_ajax_form_data({
                                    action: this.getAttribute('data-name'),
                                    value: parseInt(this.value, 10),
                                    id: this.getAttribute('data-post-id')
                                })
                            }).then(response => response.text()).then(data => {
                                message('Saved');
                            }).catch((err) => {
                                message('Error ' + err, 'error');
                            });
                        }

                        return true;
                    });
                }
            });

        </script>


        <script src="assets/js/alasql.min.js"></script>
        <script src="assets/js/data-table-23/data-table-23.js"></script>
        <script src="assets/js/generated-tables.js"></script>
        <script src="assets/js/general.js"></script>
    </body>
</html>
