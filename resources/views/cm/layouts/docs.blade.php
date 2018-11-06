@extends("cm.layouts.app")

@section("content")
  <div class="row" >
    <div class="col s2" id="bar" style="max-height: 500px; overflow-y: auto; position: sticky; top: 90px;">
        <ul class="collection with-header">
            <li class="collection-header"><h5>Apps</h5></li>
            <li class="collection-item"><a href="/docs/apps/#apps1" id="apps1s">Create New App</a></li>
            <li class="collection-item"><a href="/docs/apps/#apps2" id="apps2s">Active App Id</a></li>
            <li class="collection-item"><a href="/docs/apps/#apps3" id="apps3s">Activate App</a></li>
            <li class="collection-item"><a href="/docs/apps/#apps4" id="apps4s">Update App</a></li>
            <li class="collection-item"><a href="/docs/apps/#apps5" id="apps5s">Permissions</a></li>
            <li class="collection-item"><a href="/docs/apps/#apps6" id="apps6s">Origins</a></li>
        </ul>
        <ul class="collection with-header">
            <li class="collection-header"><h5>Tables</h5></li>
            <li class="collection-item"><a href="/docs/tables/#create_new_table" id="create_new_table_s">Create New Table</a></li>
            <li class="collection-item"><a href="/docs/tables/#add_fields" id="add_fields_s">Add Fields</a></li>
            <li class="collection-item"><a href="/docs/tables/#rename_field" id="rename_field_s">Rename Field</a></li>
            <li class="collection-item"><a href="/docs/tables/#delete_field" id="delete_field_s">Delete Field</a></li>
            <li class="collection-item"><a href="/docs/tables/#add_index" id="add_index_s">Add Index</a></li>
            <li class="collection-item"><a href="/docs/tables/#remove_index" id="remove_index_s">Remove Index</a></li>
            <li class="collection-item"><a href="/docs/tables/#crud" id="crud_s">CRUD</a></li>
            <li class="collection-item"><a href="/docs/tables/#rename_table" id="rename_table_s">Rename Table</a></li>
            <li class="collection-item"><a href="/docs/tables/#truncate_table" id="truncate_table_s">Truncate Table</a></li>
            <li class="collection-item"><a href="/docs/tables/#delete_table" id="delete_table">Delete Table</a></li>
            <li class="collection-item"><a href="/docs/tables/#export_table" id="export_table_s">Export Table</a></li>
            <li class="collection-item"><a href="/docs/tables/#import_create" id="import_create_s">Import - Create</a></li>
            <li class="collection-item"><a href="/docs/tables/#import_update" id="import_update_s">Import - Update</a></li>
            <li class="collection-item"><a href="/docs/tables/#api_calls_for_tables" id="api_calls_for_tables_s">Api Calls</a></li>
        </ul>
    </div>
    <div class="col s9" id="foo" style="min-height: 100vh">
      @yield('docs')
    </div>
  </div>
</div>
@guest
<footer class="page-footer blue darken-2">
  <div class="container">
    <div class="row">
      <div class="col l6 s12">
        <h5 class="white-text">Footer Content</h5>
        <p class="grey-text text-lighten-4">You can use rows and columns here to organize your footer content.</p>
      </div>
      <div class="col l4 offset-l2 s12">
        <h5 class="white-text">Links</h5>
        <ul>
          <li><a class="grey-text text-lighten-3" href="#!">Link 1</a></li>
          <li><a class="grey-text text-lighten-3" href="#!">Link 2</a></li>
          <li><a class="grey-text text-lighten-3" href="#!">Link 3</a></li>
          <li><a class="grey-text text-lighten-3" href="#!">Link 4</a></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="footer-copyright">
    <div class="container">
    © 2018 Copyright Rights Reserved
    <a class="grey-text text-lighten-4 right" href="#!">More Links</a>
    </div>
  </div>
</footer>
@endguest
<script>
    function visibleBottom(el) {    
        var $el = $(el),
            scrollTop = $(this).scrollTop(),
            scrollBot = scrollTop + $(this).height(),
            elTop = $el.offset().top,
            elBottom = elTop + $el.outerHeight(),
            visibleBottom = elBottom > scrollBot ? scrollBot : elBottom;
            return visibleBottom;
    }
    function visibleTop(el) {    
        var $el = $(el),
            scrollTop = $(this).scrollTop(),
            scrollBot = scrollTop + $(this).height(),
            elTop = $el.offset().top,
            elBottom = elTop + $el.outerHeight(),
            visibleTop = elTop < scrollTop ? scrollTop : elTop;
            return visibleTop;
    }
    function getVisible(el) {    
        var $el = $(el),
            scrollTop = $(this).scrollTop(),
            scrollBot = scrollTop + $(this).height(),
            elTop = $el.offset().top,
            elBottom = elTop + $el.outerHeight(),
            visibleTop = elTop < scrollTop ? scrollTop : elTop,
            visibleBottom = elBottom > scrollBot ? scrollBot : elBottom;
            return visibleBottom - visibleTop;
    }
    function isElementInViewport (el) {
        if (typeof jQuery === "function" && el instanceof jQuery) {
            el = el[0];
        }
        var rect = el.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && 
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }
    function onVisibilityChange(el, callback) {
        var old_visible;
        return function () {
            var visible = isElementInViewport(el);
            if (visible != old_visible) {
                old_visible = visible;
                if (typeof callback == 'function') {
                    callback();
                }
            }
        }
    }
    var lastScrollTop; var lastHeight= getVisible("#foo");
    function onscroll(event){
        var st = $(this).scrollTop();
        var hv = getVisible("#foo");
        if (st > lastScrollTop){
            if(hv<lastHeight){
                $("#bar").css('max-height', hv-100);
            }
        } else {
           if(hv>lastHeight){
                $("#bar").css('max-height', hv-100);
            }
        }
       lastScrollTop = st;
       lastHeight = hv;
    }
</script>
@endsection
