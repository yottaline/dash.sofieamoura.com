<div class="text-center text-secondary py-5">
    <div data-ng-if="loading">
        <span class="loading-spinner spinner-border spinner-border-sm text-secondary me-2" role="status"></span>
        <span>Loading...</span>
    </div>

    <div data-ng-if="list.length && noMore">
        No Further Data
    </div>

    <div data-ng-if="!loading && !list.length">
        <i class="bi bi-exclamation-circle display-3"></i>
        <h5>No Data</h5>
    </div>
    <script>
        $(function() {
            $(window).scroll(function() {
                if ($(window).scrollTop() >= ($(document).height() - $(window).height() - 80) &&
                    !scope.loading && !scope.noMore) scope.dataLoader();
            });
        });
    </script>
</div>
