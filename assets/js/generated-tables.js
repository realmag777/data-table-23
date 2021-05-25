'use strict';
class TABLEON_GeneratedTables extends DataTable23 {
    constructor(table_data, table_html_id, additional = {}) {
        super(table_data, table_html_id, additional);
        if (this.settings.stop_notice.length > 0) {
            return;
        }

        this.wrapper.parentElement.querySelectorAll('.tableon-text-search').forEach((input) => {
            this.attach_keyup_event(input);
            this.attach_mouseup_event(input);
        });
    }

    do_after_draw() {
        super.do_after_draw();
        document.dispatchEvent(new CustomEvent('tableon-do-after-draw', {detail: {otable: this}}));
        if (this.container.classList.contains('tableon-define-display-cell-info')) {
            this.define_display_cell_info();
        }


        //scroll bar is for posts tables
        if (this.show_compact) {

            if (this.scrollbar23) {
                this.scrollbar23.remove();
                this.wrapper.classList.remove('horizontal-scrollbar23-attached');
            }

        } else {

            if (this.container.classList.contains('tableon-data-table')) {
                if (!this.wrapper.classList.contains('horizontal-scrollbar23-attached')) {
                    setTimeout(() => {
                        this.wrapper.classList.add('horizontal-scrollbar23-attached');
                        if (typeof this.additional.no_scroll_bar === 'undefined') {
                            //this.scrollbar23 = new HorizontalScrollbar23(this.wrapper);
                        }
                    }, 777);
                }
            }

        }

        //***
        //load tables in the cells
        if (this.table.querySelectorAll('.tableon-data-table').length) {
            this.table.querySelectorAll('.tableon-data-table').forEach((t) => {
                let id = t.getAttribute('id');
                new TABLEON_GeneratedTables(JSON.parse(this.table.querySelector(`[data-table-id="${id}"]`).innerText), id, {no_scroll_bar: true});
            });
        }

        //for hidden elements (as range slider) to redraw correctly (after creating in hidden container)
        window.dispatchEvent(new Event('resize'));
    }

    recreate_scroll_bar() {
        if (this.scrollbar23) {
            this.scrollbar23.remove();
            this.wrapper.classList.remove('horizontal-scrollbar23-attached');
        }

        this.wrapper.classList.add('horizontal-scrollbar23-attached');
        //this.scrollbar23 = new HorizontalScrollbar23(this.wrapper);
    }

    //use this for class filter also
    attach_keyup_event(input) {
        let _this = this;
        input.addEventListener('keyup', function (e) {

            let add = {};
            let do_search = false;
            let key = this.getAttribute('data-key');
            if (e.keyCode === 13 || typeof e.detail.woo_text_search !== 'undefined') {

                if (input.value.length === 0) {
                    if (input.classList.contains('tableon-not-ready-text-search')) {
                        //if user want to reset by empty string nad Enter key
                        if (typeof _this.request_data.filter_data[key] !== 'undefined') {
                            input.classList.remove('tableon-not-ready-text-search');
                            delete _this.request_data.filter_data[key];
                            _this.settings.total_rows_count = 0;
                            _this.draw_pagination();
                            if (!_this.allow_reset) {
                                _this.allow_reset_force = true; //fix for load more button
                            }
                            _this.draw_data();
                            return true;
                        }
                    }
                }

                //***

                if (input.value.length < Number(input.getAttribute('minlength'))) {
                    input.classList.add('tableon-not-ready-text-search');
                    return true;
                }

                //woo text search works - table clean for new search request
                if (typeof e.detail.woo_text_search !== 'undefined') {
                    _this.reset();
                    _this.settings.total_rows_count = 0;
                    _this.draw_pagination();
                }


                add = {};
                add[key] = input.value;
                do_search = true;
            }

            if (e.keyCode === 27) {
                delete _this.request_data.filter_data[key];
                do_search = true;
                input.classList.remove('tableon-not-ready-text-search');
            }


            if (do_search) {
                input.classList.remove('tableon-not-ready-text-search');
                _this.request_data.current_page = 0;
                if (_this.request_data.filter_data && typeof _this.request_data.filter_data !== 'object' && _this.request_data.filter_data.length > 0) {
                    _this.request_data.filter_data = JSON.parse(_this.request_data.filter_data);
                }
                _this.request_data.filter_data = _this.extend(_this.request_data.filter_data, add);
                if (!_this.allow_reset) {
                    _this.allow_reset_force = true; //fix for load more button
                }

                //_this.use_cache = false;//23-04-2020 disabled as this flag disable cache after text loading
                _this.draw_data();
            }

            //***

            document.dispatchEvent(new CustomEvent('tableon-filter-is-changed', {detail: {
                    dt: _this
                }}));
        });
    }

    //use this for class filter also
    attach_mouseup_event(input) {
        let _this = this;
        //click on cross
        input.addEventListener('mouseup', function (e) {
            e.stopPropagation();
            if (input.value.length > 0) {
                setTimeout(() => {
                    if (input.value.length === 0) {
                        input.classList.remove('tableon-not-ready-text-search');
                        delete _this.request_data.filter_data[this.getAttribute('data-key')];
                        _this.request_data.current_page = 0;
                        _this.draw_data();
                        document.dispatchEvent(new CustomEvent('tableon-filter-is-changed', {detail: {
                                dt: _this
                            }}));
                    }
                }, 5);
            }
        });
    }

    create_id(prefix = '') {
        return prefix + Math.random().toString(36).substring(7);
    }

    init_actions() {
        //add keyboard navigation to the gallery, etc...
        document.addEventListener('keydown', e => {

            if (document.querySelectorAll('.tableon-gallery-lightbox:target').length > 0) {
                let current = null;

                switch (e.keyCode) {
                    case 37:
                        //left
                        current = document.querySelector('.tableon-gallery-lightbox:target .tableon-gallery-nav-left a');
                        if (current) {
                            location.hash = current.hash;
                        }
                        break;

                    case 39:
                        //right
                        current = document.querySelector('.tableon-gallery-lightbox:target .tableon-gallery-nav-right a');
                        if (current) {
                            location.hash = current.hash;
                        }
                        break;

                    case 27:
                        //escape
                        current = document.querySelector('.tableon-gallery-lightbox:target a.tableon-gallery-close');
                        if (current) {
                            location.hash = current.hash;
                        }
                        break;
                }
            }

            //+++
            //close text popup (content, excerpt)
            if (e.keyCode === 27) {
                if (document.querySelector('.tableon-more-less-container-active')) {
                    tableon_close_txt_container(document.querySelector('.tableon-more-less-container-active'));
                }
            }

        });


        //posts gallery eventization
        if ('ontouchstart' in document.documentElement) {
            document.addEventListener('touchstart', e => {
                touch_start_x = e.touches[0].clientX;
            });

            document.addEventListener('touchend', e => {
                if (document.querySelectorAll('.tableon-gallery-lightbox:target').length > 0) {
                    let current = null;

                    let end_x = e.changedTouches[0].clientX;

                    if (Math.abs(touch_start_x - end_x) > 20) {
                        if (touch_start_x > end_x) {
                            //right
                            current = document.querySelector('.tableon-gallery-lightbox:target .tableon-gallery-nav-right a');
                        } else {
                            //left
                            current = document.querySelector('.tableon-gallery-lightbox:target .tableon-gallery-nav-left a');
                        }

                        if (current) {
                            location.hash = current.hash;
                        }
                    }
                }
            });
        }
    }

}

