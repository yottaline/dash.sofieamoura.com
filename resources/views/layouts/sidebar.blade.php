 <div class="offcanvas offcanvas-start" tabindex="-1" id="navOffcanvas">
     <script>
         const navTarget = 'target';
         $(function() {
             $(`.nav-${navTarget} b`).addClass('text-danger');
         });
     </script>
     <div class="offcanvas-body">
         <ul class="list-group list-group-flush">
             <li class="list-group-item nav-dashboard">
                 <a class="link-dark d-block" href="/">
                     <i class="bi bi-speedometer text-secondary me-2"></i><b>Dashboard</b>
                 </a>
             </li>

             <li class="list-group-item nav-support">
                 <a class="link-dark d-block" href="/retailers/">
                     <i class="bi bi-person-lines-fill text-secondary me-2"></i><b>Retailers</b>
                 </a>
             </li>

             <li class="list-group-item nav-support">
                 <a class="link-dark d-block" href="/ws_products/">
                     <i class="bi bi-box-seam text-secondary me-2"></i><b>Wholesale Products</b>
                 </a>
             </li>

             <li class="list-group-item nav-support">
                 <a class="link-dark d-block" href="/ws_orders/">
                     <i class="bi bi-clipboard2-check-fill text-secondary me-2"></i><b>Wholesale
                         Orders</b>
                 </a>
             </li>

             <li class="list-group-item nav-support">
                 <a class="link-dark d-block" href="/users/">
                     <i class="bi bi-people-fill text-secondary me-2"></i><b>Users</b>
                 </a>
             </li>

             <li class="list-group-item">
                 <a class="link-dark d-block" data-bs-toggle="collapse" href="#settingCollapse" role="button"
                     aria-expanded="false" aria-controls="settingCollapse">
                     <i class="bi bi-gear text-secondary me-2"></i><b>Settings</b>
                 </a>
                 <div class="collapse" id="settingCollapse">
                     <ul class="list-group list-group-flush">
                         <li class="list-group-item nav-support">
                             <a class="link-dark d-block" href="/locations/">
                                 <i class="bi bi-globe-asia-australia text-secondary me-2"></i><b>Locations</b>
                             </a>
                         </li>

                         <li class="list-group-item nav-support">
                             <a class="link-dark d-block" href="/currencies/">
                                 <i class="bi bi-currency-exchange text-secondary me-2"></i><b>Currencies</b>
                             </a>
                         </li>

                         <li class="list-group-item nav-trans">
                             <a class="link-dark d-block" href="/categories/">
                                 <i class="bi bi-grid text-secondary me-2"></i><b>Categories</b>
                             </a>
                         </li>

                         <li class="list-group-item nav-trans">
                             <a class="link-dark d-block" href="/seasons/">
                                 <i class="bi bi-brilliance text-secondary me-2"></i><b>Seasons</b>
                             </a>
                         </li>

                         <li class="list-group-item nav-trans">
                             <a class="link-dark d-block" href="/sizes/">
                                 <i class="bi bi-arrows-fullscreen text-secondary me-2"></i><b>Sizes</b>
                             </a>
                         </li>
                     </ul>
                 </div>
                 <script>
                     const settingCollapse = new bootstrap.Collapse('#settingCollapse', {
                         toggle: false
                     });
                     if (['subsc', 'trans', 'refunds', 'promos'].includes(navTarget))
                         settingCollapse.show();
                 </script>
             </li>
         </ul>
     </div>
     <div class="d-flex">
         <a href="#" class="d-block p-3 flex-grow-1 border-top rounded-0 link-dark">
             <i class="bi bi-person-circle text-warning me-2"></i>
             <b>{{ auth()->user()->user_name }}</b>
         </a>
         <form action="{{ route('logout') }}" method="post" class="d-block p-2 border-top border-start rounded-0">
             @csrf
             <button type="submit" class="btn border-0"><i class="bi bi-power text-danger"></i></button>
         </form>
     </div>
 </div>
