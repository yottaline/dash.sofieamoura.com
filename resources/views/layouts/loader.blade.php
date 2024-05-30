<div class="text-center text-secondary">
    <div ng-if="!list.length" class="py-5">
        <i class="bi bi-exclamation-circle display-3"></i>
        <h5>No Data</h5>
    </div>
    <div ng-if="loading">
        <span class="loading-spinner spinner-border spinner-border-sm text-secondary me-2" role="status"></span>
        <span>Loading...</span>
    </div>
    <div ng-if="list.length && noMore">
        No Further Data
    </div>
    <script>
        $(function() {
            $(window).scroll(function() {
                if ($(window).scrollTop() >= ($(document).height() - $(window).height() - 80) &&
                    !scope.loading && !scope.noMore) scope.load();
            });
        });
    </script>
</div>
